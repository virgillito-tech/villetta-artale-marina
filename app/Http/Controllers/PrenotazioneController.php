<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sabre\VObject;
use Illuminate\Support\Facades\Log;
use App\Models\Prenotazione;
use App\Models\PrezzoGiornaliero; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class PrenotazioneController extends Controller
{
    // Front-end: calendario prenotazioni
    public function indexCalendario()
    {
        $icalUrl = 'https://ical.booking.com/v1/export?t=3dac0c9d-8cd1-4eea-8425-ea811fc9013c';
        $busyDates = [];

        try {
            $icalContent = @file_get_contents($icalUrl);
            if ($icalContent) {
                $vcalendar = \Sabre\VObject\Reader::read($icalContent);

                $events = is_iterable($vcalendar->VEVENT) ? $vcalendar->VEVENT : [$vcalendar->VEVENT];

                foreach ($events as $event) {
                    $start = $event->DTSTART->getDateTime()->format('Y-m-d');
                    $endDate = $event->DTEND->getDateTime()->modify('+1 day')->format('Y-m-d');

                    $period = new \DatePeriod(
                        new \DateTime($start),
                        new \DateInterval('P1D'),
                        new \DateTime($endDate)
                    );

                    foreach ($period as $date) {
                        $busyDates[$date->format('Y-m-d')] = [
                            'title' => 'Prenotato su Booking',
                            'start' => $date->format('Y-m-d'),
                            'allDay' => true,
                            'color' => '#dc3545',
                            'description' => 'Prenotato su Booking'
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error("Errore parsing iCal: " . $e->getMessage());
        }

        $dbEvents = \App\Models\Prenotazione::all()->map(function ($p) {
            return [
                'title' => $p->nome ?? 'Prenotazione DB',
                'start' => $p->data_inizio,
                'end' => $p->data_fine,
                'allDay' => true,
                'color' => '#0d6efd',
                'description' => 'Prenotazione diretta dal sito'
            ];
        })->toArray();

        $events = array_merge(array_values($busyDates), $dbEvents);

        return view('partials.calendario', [
        'prenotazioni' => $events
    ]);

    }

    // Admin: gestione prenotazioni
    public function adminIndex()
    {
        $prenotazioni = \App\Models\Prenotazione::with([])
            ->orderBy('data_inizio', 'desc')
            ->get()
            ->map(function ($prenotazione) {
                return [
                    'id' => $prenotazione->id,
                    'title' => $prenotazione->nome . ' (' . $prenotazione->numero_persone . ' pax)',
                    'start' => $prenotazione->data_inizio,
                    'end' => $prenotazione->data_fine,
                    'backgroundColor' => $this->getColorByStato($prenotazione->stato),
                    'borderColor' => $this->getColorByStato($prenotazione->stato),
                    'extendedProps' => [
                        'email' => $prenotazione->email,
                        'telefono' => $prenotazione->telefono,
                        'stato' => $prenotazione->stato,
                        'note' => $prenotazione->note,
                    ],
                ];
            });

        return view('admin.prenotazioni.index', compact('prenotazioni'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'data_inizio' => 'required|date|after_or_equal:today',
            'data_fine' => 'required|date|after:data_inizio',
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:25',
            'numero_stanze' => 'required|integer|min:1|max:10',
            'numero_persone' => 'required|integer|min:1|max:20',
            'note' => 'nullable|string|max:1000',
        ]);

        $inizio = Carbon::parse($data['data_inizio']);
        $fine = Carbon::parse($data['data_fine']);

        // Genera elenco date del soggiorno
        $dates = [];
        for ($date = $inizio->copy(); $date->lte($fine); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        // 1. Controlla date chiuse
        $closedDates = PrezzoGiornaliero::whereIn('data', $dates)
            ->where('is_closed', true)
            ->pluck('data');

        if ($closedDates->isNotEmpty()) {
            return back()->with('error', 'Le date non disponibili: ' . $closedDates->implode(', '));
        }

        // 2. Controlla prenotazioni esistenti
        $existingBookings = Prenotazione::where(function($query) use ($inizio, $fine) {
            $startCopy = $inizio->copy();
            $endCopy = $fine->copy();

            $query->whereBetween('data_inizio', [$startCopy, $endCopy])
                ->orWhereBetween('data_fine', [$startCopy, $endCopy])
                ->orWhere(function($q) use ($startCopy, $endCopy) {
                    $q->where('data_inizio', '<=', $startCopy)
                    ->where('data_fine', '>=', $endCopy);
                });
        })->whereIn('stato', ['confermata', 'in attesa'])->exists();

        if ($existingBookings) {
            return back()->with('error', 'Le date selezionate risultano già prenotate.');
        }

        // ✅ Salvataggio prenotazione
        $data['stato'] = 'confermata';
        $data['origine'] = 'web';
        $prenotazione = Prenotazione::create($data);

        // Invio email
        try {
            $this->sendConfirmationEmail($prenotazione);
        } catch (\Exception $e) {
            \Log::warning('Errore invio email: ' . $e->getMessage());
        }

        $codicePrenotazione = 'VIL-' . str_pad($prenotazione->id, 4, '0', STR_PAD_LEFT);

        return redirect('/')->with('success', "Prenotazione confermata! Codice: $codicePrenotazione");
    }



    public function getPrezziJson()
    {
        $prezzi = PrezzoGiornaliero::all()->map(function($p) {
            return [
                'title' => number_format($p->prezzo_1, 2, ',', '.') . ' €', // puoi mostrare 1 pax o media
                'start' => $p->data->toDateString(),
                'allDay' => true
            ];
        });

        return response()->json($prezzi);
    }

    public function search(Request $request)
    {
        $request->validate([
            'data_inizio' => 'required|date|after_or_equal:today',
            'data_fine' => 'required|date|after:data_inizio',
            'rooms' => 'required|integer|min:1|max:3',
            'guests' => 'required|integer|min:1|max:6',
        ]);

        $inizio = Carbon::parse($request->data_inizio);
        $fine = Carbon::parse($request->data_fine);

        // Genera elenco date
        $dates = [];
        for ($date = $inizio->copy(); $date->lte($fine); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        // ✅ Inizializza variabili
        $isAvailable = true;
        $message = '';

        // Controllo date chiuse
        $closedDates = \App\Models\PrezzoGiornaliero::whereIn('data', $dates)
            ->where('is_closed', true)
            ->pluck('data')
            ->toArray();

        if (!empty($closedDates)) {
            $isAvailable = false;
            $message = 'Le date non disponibili: ' . implode(', ', $closedDates);
        }

        // Controllo prenotazioni esistenti
        $existingBookings = \App\Models\Prenotazione::where(function($query) use ($inizio, $fine) {
            $startCopy = $inizio->copy();
            $endCopy = $fine->copy();

            $query->whereBetween('data_inizio', [$startCopy, $endCopy])
                ->orWhereBetween('data_fine', [$startCopy, $endCopy])
                ->orWhere(function($q) use ($startCopy, $endCopy) {
                    $q->where('data_inizio', '<=', $startCopy)
                    ->where('data_fine', '>=', $endCopy);
                });
        })->where('stato', '!=', 'annullata')->exists();

        if ($existingBookings) {
            $isAvailable = false;
            $message = 'Alcune date risultano già prenotate.';
        }

        // Calcola prezzo (solo se disponibile)
        $prezzoTotale = null;
        if ($isAvailable) {
            $prezzoTotale = count($dates) * 100; // esempio: 100€ a notte
        }

        return view('prenotazioni.risultati', compact(
            'inizio', 'fine', 'dates', 'isAvailable', 
            'message', 'prezzoTotale', 'request'
        ));
    }



    private function sendConfirmationEmail($prenotazione)
    {
        Mail::to($prenotazione->email)->send(new PrenotazioneConfermata($prenotazione));
    }


    // Ricerca per codice prenotazione
    public function findByCode($codice)
    {
        // Estrai l'ID dal codice (es: da "VIL-0042" ottieni "42")
        $id = (int) str_replace('VIL-', '', $codice);
        
        return Prenotazione::find($id);
    }

    private function getColorByStato($stato)
    {
        return match($stato) {
            'confermata' => '#28a745',
            'in attesa' => '#ffc107', 
            'annullata' => '#dc3545',
            default => '#6c757d'
        };

    }

    public function updateStato(Request $request, $id)
    {
        $prenotazione = Prenotazione::findOrFail($id);
        
        $request->validate([
            'stato' => 'required|in:confermata,in attesa,annullata'
        ]);
        
        $prenotazione->update(['stato' => $request->stato]);
        
        // Invia email di aggiornamento se necessario
        if ($request->stato === 'confermata') {
            Mail::to($prenotazione->email)->send(new PrenotazioneConfermata($prenotazione));
        }
        
        return back()->with('success', 'Stato prenotazione aggiornato');
    }



    public function userPrenotazioni()
    {
        $prenotazioni = Prenotazione::where('email', Auth::user()->email)
            ->orderBy('data_inizio', 'desc')
            ->get();

        return view('user.prenotazioni', compact('prenotazioni'));
    }




}    