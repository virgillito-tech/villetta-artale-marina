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
use Stripe\Stripe;
use Stripe\Refund;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Mail\PrenotazioneAnnullata;
use Illuminate\Support\Facades\Cache;
use App\Mail\NotificaAnnullamentoAdmin;

class PrenotazioneController extends Controller
{
    // app/Http/Controllers/PrenotazioneController.php

    private function getBookingEventsFromIcal()
    {
        // Mettiamo in cache per 30 minuti (1800 secondi)
        return Cache::remember('ical_events', 1800, function () {
            $icalUrl = 'https://ical.booking.com/v1/export?t=3dac0c9d-8cd1-4eea-8425-ea811fc9013c';
            $busyDates = [];

            try {
                $context = stream_context_create(['http' => ['timeout' => 5]]);
                $icalContent = @file_get_contents($icalUrl, false, $context);

                if ($icalContent) {
                    $vcalendar = \Sabre\VObject\Reader::read($icalContent);
                    $events = is_iterable($vcalendar->VEVENT) ? $vcalendar->VEVENT : [$vcalendar->VEVENT];

                    foreach ($events as $event) {
                        
                        // 1. FILTRO TRASPARENZA (ignora eventi "liberi")
                        if (isset($event->TRANSP) && (string)$event->TRANSP === 'TRANSPARENT') {
                            continue;
                        }

                        // 🎯 2. NUOVO FILTRO SOMMARIO (ignora eventi "prezzo") 🎯
                        if (isset($event->SUMMARY)) {
                            $summary = strtolower((string)$event->SUMMARY);
                            
                            // Se il titolo dell'evento contiene '€' o 'disponibile', 
                            // NON è una prenotazione. Lo saltiamo.
                            if (str_contains($summary, '€') || 
                                str_contains($summary, '$') ||
                                str_contains($summary, 'available') || 
                                str_contains($summary, 'disponibile')) 
                            {
                                continue; // Salta questo evento, è un prezzo.
                            }
                        }
                        // 🎯 FINE NUOVO FILTRO 🎯


                        // Se l'evento è arrivato fin qui, è una prenotazione reale (o "chiuso")
                        $start = $event->DTSTART->getDateTime()->format('Y-m-d');
                        $endDate = $event->DTEND->getDateTime()->format('Y-m-d');

                        // Genera tutte le date nel range
                        $period = new \DatePeriod(
                            new \DateTime($start),
                            new \DateInterval('P1D'),
                            new \DateTime($endDate)
                        );

                        foreach ($period as $date) {
                            if ($date->format('Y-m-d') == $endDate) continue;
                            $formattedDate = $date->format('Y-m-d');
                            $busyDates[$formattedDate] = $formattedDate;
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error("Errore parsing iCal: " . $e->getMessage());
            }

            return array_keys($busyDates);
        });
    }

    // Front-end: calendario prenotazioni
    public function indexCalendario()
    {
        $locale = app()->getLocale();
        
        // 1. Recupera date occupate da Booking (già filtrate)
        $bookingDates = $this->getBookingEventsFromIcal();
        $icalEvents = [];

        foreach ($bookingDates as $date) {
            $icalEvents[] = [
                'title' => 'Prenotato su Booking',
                'start' => $date,
                'allDay' => true,
                'color' => '#dc3545',
                'description' => 'Prenotato su Booking'
            ];
        }

        // 2. Recupera date dal DB locale
        $dbEvents = \App\Models\Prenotazione::where('stato', '!=', 'annullata') // Importante: non mostrare le annullate
            ->get()
            ->map(function ($p) {
                return [
                    'title' => $p->nome ?? 'Prenotazione Web',
                    'start' => $p->data_inizio,
                    'end' => $p->data_fine, // FullCalendar gestisce l'end date esclusiva automaticamente se allDay=true
                    'allDay' => true,
                    'color' => '#0d6efd',
                    'description' => 'Prenotazione diretta dal sito'
                ];
            })->toArray();

        $events = array_merge($icalEvents, $dbEvents);

        return view($locale . '.partials.calendario', [
            'locale' => $locale,
            'prenotazioni' => $events
        ]);
    }

    // Admin: gestione prenotazioni (MODIFICATA PER TABELLA E FILTRI)
    public function adminIndex(Request $request)
    {
        $locale = app()->getLocale();
        
        // Inizia la query
        $query = Prenotazione::query();

        // 1. Gestione Ricerca
        if ($request->filled('search')) {
            $search = $request->input('search');
            // Pulisce il codice prenotazione (es. "VIL-0004" -> "4")
            $searchId = ltrim(str_ireplace('VIL-', '', $search), '0');

            $query->where(function($q) use ($search, $searchId) {
                $q->where('nome', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
                
                if (is_numeric($searchId) && $searchId > 0) {
                    $q->orWhere('id', $searchId);
                }
            });
        }

        // 2. Gestione Filtri
        if ($request->filled('stato')) {
            $query->where('stato', $request->input('stato'));
        }

        if ($request->filled('tipo_pagamento')) {
            $query->where('tipo_pagamento', $request->input('tipo_pagamento'));
        }

        if ($request->filled('numero_persone')) {
            $query->where('numero_persone', $request->input('numero_persone'));
        }

        // 3. Ordinamento e Paginazione
        $prenotazioni = $query->orderBy('data_inizio', 'desc')
                              ->paginate(20)
                              ->withQueryString(); // Aggiunge i filtri ai link di paginazione

        return view($locale . '.admin.prenotazioni.index', compact('prenotazioni', 'locale'));
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
            'prezzo_totale' => 'required|float|min:0',
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

        // Invia email all'amministratore
        \Mail::to('villettartalemarina@gmail.com')
            ->send(new \App\Mail\NuovaPrenotazioneAdmin($prenotazione));

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
        $locale = app()->getLocale();
        $request->validate([
            'data_inizio' => 'required|date|after_or_equal:today',
            'data_fine' => 'required|date|after:data_inizio',
            'rooms' => 'required|integer|min:1|max:3',
            'guests' => 'required|integer|min:1|max:6',
        ]);

        $inizio = Carbon::parse($request->data_inizio);
        $fine = Carbon::parse($request->data_fine);

        // Genera elenco date del soggiorno (le notti)
        // Es: Arrivo 10, Partenza 12 -> Notti: 10, 11
        $dates = [];
        for ($date = $inizio->copy(); $date->lt($fine); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        // ✅ Inizializza variabili
        $isAvailable = true;
        $message = '';

        // 1. Controllo date chiuse manualmente
        $closedDates = \App\Models\PrezzoGiornaliero::whereIn('data', $dates)
            ->where('is_closed', true)
            ->pluck('data')
            ->toArray();

        if (!empty($closedDates)) {
            $isAvailable = false;
            $message = 'Le date non disponibili: ' . implode(', ', $closedDates);
        }

        // 2. Controllo date occupate su Booking.com (solo se ancora disponibile)
        if ($isAvailable) {
            // Recupera le date occupate da Booking tramite iCal
            $bookingDates = $this->getBookingEventsFromIcal();
            
            // Controlla se le date richieste ($dates) si sovrappongono a quelle di Booking
            $conflittiIcal = array_intersect($dates, $bookingDates);

            if (!empty($conflittiIcal)) {
                $isAvailable = false;
                $message = 'Le date risultano occupate su Booking.com: ' . implode(', ', $conflittiIcal);
            }
        }

        // 3. Controllo prenotazioni esistenti nel DB (solo se ancora disponibile)
        if ($isAvailable) {
            // Logica per trovare sovrapposizioni:
            // Trova qualsiasi prenotazione che INIZIA PRIMA della data di FINE richiesta
            // E FINISCE DOPO la data di INIZIO richiesta
            
            $existingBookings = \App\Models\Prenotazione::where('stato', '!=', 'annullata')
                ->where(function ($query) use ($inizio, $fine) {
                    $query->where('data_inizio', '<', $fine)
                          ->where('data_fine', '>', $inizio);
                })
                ->exists();

            if ($existingBookings) {
                $isAvailable = false;
                $message = 'Alcune date risultano già prenotate sul nostro sito.';
            }
        }

        // Calcola prezzo (solo se disponibile)
        $prezzoTotale = null;
        if ($isAvailable) {
            // Se le date sono 0 (es. 10/10 -> 10/10) non c'è prezzo
            if (empty($dates)) {
                 $isAvailable = false;
                 $message = 'Seleziona almeno una notte.';
            } else {
                $prezzi = \App\Models\PrezzoGiornaliero::whereIn('data', $dates)->get();

                // Controlla se abbiamo un prezzo per OGNI notte richiesta
                if ($prezzi->count() === count($dates)) {
                    // Somma i prezzi giornalieri in base alle persone
                    $colonnaPrezzo = 'prezzo_' . $request->guests;
                    $prezzoTotale = $prezzi->sum($colonnaPrezzo);
                } else {
                    $isAvailable = false;
                    $message = 'Non è stato definito un prezzo per tutte le date selezionate.';
                }
            }
        }

        return view($locale . '.prenotazioni.risultati', compact(
            'inizio', 'fine', 'dates', 'isAvailable', 
            'message', 'prezzoTotale', 'request', 'locale'
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
        
        // Controlla se sta per essere annullata (prima che venga aggiornata)
        $stavaPerEssereAnnullata = $request->stato === 'annullata' && $prenotazione->stato !== 'annullata';

        $prenotazione->update(['stato' => $request->stato]);
        
        // Invia email di aggiornamento se necessario
        if ($request->stato === 'confermata') {
            Mail::to($prenotazione->email)->send(new \App\Mail\PrenotazioneConfermata($prenotazione));
        }

        // 🎯 INVIA NOTIFICA ALL'ADMIN SE È STATA ANNULLATA 🎯
        if ($stavaPerEssereAnnullata) {
            $annullataDa = 'Admin (da Dashboard)';
            $rimborso = 'No (Stato Aggiornato)';
            try {
                 Mail::to('villettartalemarina@gmail.com')
                    ->send(new NotificaAnnullamentoAdmin($prenotazione, $annullataDa, $rimborso));
            } catch (\Exception $e) {
                 \Log::warning('Errore invio email ADMIN annullamento: ' . $e->getMessage());
            }
        }
        // 🎯 FINE BLOCCO 🎯
        
        return back()->with('success', 'Stato prenotazione aggiornato');
    }


    public function userPrenotazioni()
    {
        $locale = app()->getLocale();
        $prenotazioni = Prenotazione::where('email', Auth::user()->email)
            ->orderBy('data_inizio', 'desc')
            ->get();

        return view($locale . '.user.prenotazioni', compact('prenotazioni', 'locale'));
    }

    public function annulla(Prenotazione $prenotazione)
    {
        //Controllo di sicurezza: l'utente può annullare solo le sue prenotazioni
        if (auth()->id() !== $prenotazione->confermata_da_id) {
            abort(403, 'Azione non autorizzata.');
        }

        //Controllo dello stato: non annullare se già annullata
        if ($prenotazione->stato === 'annullata') {
            return back()->with('error', 'Questa prenotazione è già stata annullata.');
        }

        // Controllo dei 30 giorni (basato sulla data di check-in)
        $dataInizio = Carbon::parse($prenotazione->data_inizio);
        $oggi = now()->startOfDay();
        $giorniMancanti = $oggi->diffInDays($dataInizio, false); // 'false' permette valori negativi

        // Se mancano 30 giorni o meno (o la data è passata), blocca.
        if ($giorniMancanti <= 30) {
            return back()->with('error', 'Annullamento non consentito. Mancano 30 giorni o meno alla data di arrivo.');
        }

        try {
            //Esegui il rimborso
            $this->eseguiRimborso($prenotazione);

            //Aggiorna lo stato nel database
            $prenotazione->update(['stato' => 'annullata']);

            try {
                Mail::to($prenotazione->email)->send(new PrenotazioneAnnullata($prenotazione));
            } catch (\Exception $e) {
                Log::warning("Errore invio email annullamento per #{$prenotazione->id}: " . $e->getMessage());
                // Non bloccare il successo del rimborso se l'email fallisce
            }

            try {
                Mail::to('villettartalemarina@gmail.com')
                    ->send(new NotificaAnnullamentoAdmin($prenotazione, 'Utente', 'Sì (Automatico)'));
            } catch (\Exception $e) {
                Log::warning("Errore invio email ADMIN annullamento per #{$prenotazione->id}: " . $e->getMessage());
            }

            return back()->with('success', 'Prenotazione annullata e rimborso avviato con successo.');

        } catch (\Exception $e) {
            Log::error("Errore rimborso prenotazione #{$prenotazione->id}: " . $e->getMessage());
            return back()->with('error', 'Si è verificato un errore durante il tentativo di rimborso. Contatta assistenza.');
        }
    }

    private function eseguiRimborso(Prenotazione $prenotazione)
    {
        if (!$prenotazione->payment_gateway_id) {
            throw new \Exception('ID pagamento non trovato, impossibile rimborsare automaticamente.');
        }

        if ($prenotazione->tipo_pagamento === 'stripe') {
            
            Stripe::setApiKey(config('services.stripe.secret'));
            Refund::create([
                'payment_intent' => $prenotazione->payment_gateway_id,
                'amount' => $prenotazione->prezzo_totale * 100, // Stripe usa i centesimi!
                'reason' => 'requested_by_customer',
            ]);

        } elseif ($prenotazione->tipo_pagamento === 'paypal') {

            $provider = new PayPalClient(config('services.paypal'));
            $provider->getAccessToken();

            $importoInEuro = number_format($prenotazione->prezzo_totale, 2, '.', '');

            // ⬇️ QUESTA È LA SINTASSI CORRETTA ⬇️
            $provider->refundCapturedPayment(
                $prenotazione->payment_gateway_id, // Argomento 1: Il Capture ID (string)
                '',                                // Argomento 2: Un Invoice ID (string, possiamo lasciarlo vuoto)
                $importoInEuro,                    // Argomento 3: L'importo (float/stringa numerica)
                'EUR',                             // Argomento 4: La valuta (string)
                'Rimborso per annullamento prenotazione Villetta Artale.' // Argomento 5: La nota (string)
            );
        }
    }

    public function adminDestroy(Prenotazione $prenotazione, Request $request)
    {
        // 1. Controlla se è già annullata
        if ($prenotazione->stato === 'annullata') {
            return back()->with('info', 'Questa prenotazione è già stata annullata.');
        }

        // 2. Controlla se l'admin ha richiesto un rimborso
        if ($request->has('with_refund')) {
            
            // 3. Controlla se il rimborso è possibile
            if (empty($prenotazione->payment_gateway_id)) {
                return back()->with('error', 'Impossibile rimborsare: ID pagamento non trovato.');
            }
            if ($prenotazione->stato !== 'confermata') {
                 return back()->with('error', 'Impossibile rimborsare: La prenotazione non è confermata.');
            }

            // 4. Esegui il rimborso
            try {
                $this->eseguiRimborso($prenotazione);
                // Logga l'azione dell'admin
                Log::info("Rimborso admin processato per prenotazione #{$prenotazione->id} da utente " . auth()->id());
            
            } catch (\Exception $e) {
                // Se il rimborso fallisce, FERMATI e non cancellare
                Log::error("Rimborso admin FALLITO per prenotazione #{$prenotazione->id}: " . $e->getMessage());
                return back()->with('error', 'Rimborso Fallito: ' . $e->getMessage() . '. La prenotazione non è stata cancellata.');
            }
        }

        // 5. Se nessun rimborso è stato richiesto (o se è andato a buon fine),
        // imposta lo stato su "annullata" (NON eliminare dal DB per lo storico)
        $prenotazione->update(['stato' => 'annullata']);

        // 6. Invia email di notifica all'utente
        try {
            Mail::to($prenotazione->email)->send(new PrenotazioneAnnullata($prenotazione));
        } catch (\Exception $e) {
            Log::warning("Errore invio email annullamento (admin) per #{$prenotazione->id}: " . $e->getMessage());
        }

        $annullataDa = 'Admin (' . auth()->user()->nome . ')';
        $rimborso = $request->has('with_refund') ? 'Sì (Manuale)' : 'No';
        try {
            Mail::to('villettartalemarina@gmail.com')
                ->send(new NotificaAnnullamentoAdmin($prenotazione, $annullataDa, $rimborso));
        } catch (\Exception $e) {
            Log::warning("Errore invio email ADMIN annullamento per #{$prenotazione->id}: " . $e->getMessage());
        }

        return back()->with('success', 'Prenotazione annullata con successo.');
    }


    /**
     * Genera un feed iCal (file .ics) con le prenotazioni confermate del sito.
     * Questo URL verrà importato in Booking.com per una sincronizzazione a due vie.
     */
    public function exportIcal()
    {
        // 1. Inizializza il calendario iCal usando la libreria Sabre
        $vcalendar = new VObject\Component\VCalendar();
        $vcalendar->add('PRODID', 'VillettaArtaleSyncSystem'); // Nome del tuo "programma"
        $vcalendar->add('VERSION', '2.0');
        $vcalendar->add('CALSCALE', 'GREGORIAN');

        // 2. Recupera solo le prenotazioni confermate e future dal tuo database
        $prenotazioni = Prenotazione::where('stato', 'confermata')
                                    ->where('data_fine', '>=', now()->startOfDay())
                                    ->get();

        // 3. Aggiungi ogni prenotazione come un evento "occupato"
        foreach ($prenotazioni as $prenotazione) {
            
            $vevent = $vcalendar->add('VEVENT');
            
            // Titolo dell'evento
            $vevent->add('SUMMARY', 'Occupato - Prenotazione Sito Web');
            
            // ID unico per questa prenotazione. Importante per la sincronizzazione.
            $vevent->add('UID', 'villetta-artale-id-' . $prenotazione->id . '@villettartale.com');
            
            // Imposta come evento "all-day" (di un'intera giornata)
            $start = new \DateTime($prenotazione->data_inizio);
            $end = new \DateTime($prenotazione->data_fine);

            // Aggiungi DTSTART (data inizio)
            $dtstart = $vevent->add('DTSTART', $start);
            $dtstart['VALUE'] = 'DATE'; // Specifica che è una data, non un orario

            // Aggiungi DTEND (data fine)
            $dtend = $vevent->add('DTEND', $end);
            $dtend['VALUE'] = 'DATE'; // La data di fine è esclusa (corretto per iCal)

            // Timestamp di creazione
            $vevent->add('DTSTAMP', new \DateTime());
            
            // 🎯 QUESTA È LA RIGA PIÙ IMPORTANTE 🎯
            // 'OPAQUE' significa "Occupato" (Busy). 
            // 'TRANSPARENT' significherebbe "Libero" (Free).
            $vevent->add('TRANSP', 'OPAQUE');
        }

        // 4. Serializza i dati (converti in stringa)
        $icalData = $vcalendar->serialize();

        // 5. Restituisci il file con le intestazioni corrette
        return response($icalData, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename="villetta-artale-sync.ics"',
        ]);
    }

}    