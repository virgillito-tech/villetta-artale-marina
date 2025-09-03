<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Prenotazione;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class PagamentiController extends Controller
{

    public function checkout(Request $request)
    {
        $request->validate([
            'data_inizio' => 'required|date|after_or_equal:today',
            'data_fine' => 'required|date|after:data_inizio',
            'numero_stanze' => 'required|integer|min:1|max:3',
            'numero_persone' => 'required|integer|min:1|max:6',
            'note' => 'nullable|string|max:1000',
            'importo' => 'required|integer|min:1',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $importo = $request->input('importo'); 
        $descrizione = 'Prenotazione Villetta Artale Marina';

        // Salviamo i dati della prenotazione temporanea in metadata
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $descrizione],
                    'unit_amount' => $importo,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('pagamento.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('pagamento.cancel'),
            'metadata' => [
                'data_inizio' => $request->data_inizio,
                'data_fine' => $request->data_fine,
                'numero_stanze' => $request->numero_stanze,
                'numero_persone' => $request->numero_persone,
                'note' => $request->note ?? '',
                'user_id' => auth()->id(),
            ]
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        if (!$sessionId) {
            return redirect('/')->with('error', 'Sessione di pagamento non trovata.');
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $session = \Stripe\Checkout\Session::retrieve($sessionId);

        // Controlliamo se il pagamento è stato completato
        if ($session->payment_status !== 'paid') {
            return redirect('/')->with('error', 'Pagamento non completato.');
        }

        // Creiamo la prenotazione solo ora
        $prenotazione = Prenotazione::create([
            'data_inizio' => $session->metadata->data_inizio,
            'data_fine' => $session->metadata->data_fine,
            'numero_stanze' => $session->metadata->numero_stanze,
            'numero_persone' => $session->metadata->numero_persone,
            'note' => $session->metadata->note,
            'stato' => 'confermata',
            'origine' => 'web',
            'nome' => auth()->user()->nome,
            'email' => auth()->user()->email,
            'telefono' => auth()->user()->telefono,
            'user_id' => auth()->id(),
        ]);

        // Invio email di conferma
        try {
            Mail::to($prenotazione->email)->send(new \App\Mail\PrenotazioneConfermata($prenotazione));
        } catch (\Exception $e) {
            \Log::warning('Errore invio email: ' . $e->getMessage());
        }

        $codicePrenotazione = 'VIL-' . str_pad($prenotazione->id, 4, '0', STR_PAD_LEFT);

        return redirect('/')->with('success', "Pagamento completato! Prenotazione confermata. Codice: $codicePrenotazione");
    }


    public function cancel()
    {
        return view('pagamenti.cancel');
    }


}
