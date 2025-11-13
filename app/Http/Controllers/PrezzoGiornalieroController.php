<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrezzoGiornaliero;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PrezzoGiornalieroController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();
        $prezzi = PrezzoGiornaliero::orderBy('data')->get();
        
        // Calcola statistiche
        $stats = [];
        if ($prezzi->count() > 0) {
            $prezziBase = $prezzi->pluck('prezzo_1')->map(function($p) {
                return (float) $p;
            });
            
            $stats = [
                'min' => $prezziBase->min(),
                'max' => $prezziBase->max(),
                'media' => $prezziBase->avg()
            ];
        }

        return view($locale . '.prezzi.index', compact('prezzi', 'stats', 'locale'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_range' => 'required|string',
            'prezzo_1' => 'required|numeric|min:0',
            'prezzo_2' => 'required|numeric|min:0',
            'prezzo_3' => 'required|numeric|min:0',
            'prezzo_4' => 'required|numeric|min:0',
            'prezzo_5' => 'required|numeric|min:0',
            'prezzo_6' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:500', 
            'is_closed' => 'nullable|boolean'
        ]);

        $isClosed = $request->boolean('is_closed', false);
        Log::info('Valore is_closed ricevuto:', ['is_closed' => $isClosed]);
        Log::info('Dati ricevuti:', $request->all());
        $dates = explode(" to ", $request->date_range);
        $inizio = Carbon::parse($dates[0]);
        $fine = isset($dates[1]) ? Carbon::parse($dates[1]) : $inizio;

        $savedDays = 0;
        for ($date = $inizio->copy(); $date->lte($fine); $date->addDay()) {
            $saved = PrezzoGiornaliero::updateOrCreate(
                ['data' => $date->toDateString()],
                [
                    'prezzo_1' => $request->prezzo_1,
                    'prezzo_2' => $request->prezzo_2,
                    'prezzo_3' => $request->prezzo_3,
                    'prezzo_4' => $request->prezzo_4,
                    'prezzo_5' => $request->prezzo_5,
                    'prezzo_6' => $request->prezzo_6,
                    'note' => $request->note,
                    'is_closed' => $isClosed
                ]
            );

            Log::info("Prezzo salvato per {$date->toDateString()}:", ['record' => $saved->toArray()]);

            $savedDays++;
        }

        // Rispondi sempre con JSON quando richiesto
        return response()->json([
            'success' => true,
            'message' => "Prezzi salvati per {$savedDays} giorno" . ($savedDays > 1 ? 'i' : '') . '!',
            'days_saved' => $savedDays
        ]);
    }

    public function update(Request $request, $data)
    {
        $prezzo = PrezzoGiornaliero::where('data', $data)->firstOrFail();
        
        $validated = $request->validate([
            'prezzo_1' => 'required|numeric|min:0',
            'prezzo_2' => 'required|numeric|min:0',
            'prezzo_3' => 'required|numeric|min:0',
            'prezzo_4' => 'required|numeric|min:0',
            'prezzo_5' => 'required|numeric|min:0',
            'prezzo_6' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:500',
            'is_closed' => 'nullable|boolean'
        ]);

        $prezzo->update(array_merge($validated, [
            'is_closed' => $request->boolean('is_closed')
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Prezzo aggiornato con successo'
            ]);
        }

        Log::info('UPDATE chiamata con data:', ['param' => $data]);
        Log::info('Body ricevuto:', $request->all());

        return redirect()->route('prezzi.index')->with('success', 'Prezzo aggiornato con successo');
    }

    public function destroy($data)
    {
        $prezzo = PrezzoGiornaliero::where('data', $data)->firstOrFail();
        $prezzo->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Prezzo eliminato con successo'
            ]);
        }

        return redirect()->route('prezzi.index')->with('success', 'Prezzo eliminato con successo');
    }

    public function getPrezziJson()
    {
        $prezzi = PrezzoGiornaliero::all()->map(function($p) {
            if ($p->is_closed) {
                return [
                    'title' => 'Chiuso',
                    'start' => $p->data->toDateString(),
                    'allDay' => true,
                    'backgroundColor' => '#dc3545',
                    'borderColor' => '#dc3545',
                    'textColor' => 'white',
                    'extendedProps' => [
                        'prezzi' => $p->toArray()
                    ]
                ];
            } else {
                // Eventi per giorni aperti con prezzo mostrato
                return [
                    'title' => '€' . number_format($p->prezzo_1, 0),
                    'start' => $p->data->toDateString(),
                    'allDay' => true,
                    'backgroundColor' => $this->getPriceColor($p->prezzo_1),
                    'borderColor' => $this->getPriceColor($p->prezzo_1),
                    'textColor' => $p->prezzo_1 > 100 ? 'black' : 'white',
                    'extendedProps' => [
                        'prezzi' => $p->toArray()
                    ]
                ];
            }
        });

        return response()->json($prezzi);
    }


    private function getPriceColor($prezzo)
    {
        if ($prezzo <= 100) return '#28a745'; // Verde per prezzi bassi
        if ($prezzo <= 150) return '#ffc107'; // Giallo per prezzi medi
        return '#dc3545'; // Rosso per prezzi alti
    }
}