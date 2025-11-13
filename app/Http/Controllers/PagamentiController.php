<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Prenotazione;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Log;

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
            'prezzo_totale' => 'required|integer|min:1',
            'tipo_pagamento' => 'required|in:stripe,paypal', 
        ]);

        $importo = $request->input('prezzo_totale');
        $descrizione = 'Prenotazione Villetta Artale Marina';

        if ($request->tipo_pagamento === 'stripe') {
            return $this->checkoutStripe($request, $importo, $descrizione);
        }

        return $this->checkoutPayPal($request, $importo, $descrizione);
    }

    private function checkoutStripe(Request $request, $importo, $descrizione)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
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
                'confermata_da_id' => auth()->id(),
                'tipo_pagamento' => 'stripe',
                'prezzo_totale' => $importo,
            ]
        ]);

        return redirect($session->url);
    }

    private function checkoutPayPal(Request $request, $importo, $descrizione)
    {
        $provider = new PayPalClient(config('services.paypal'));

        $paypalToken = $provider->getAccessToken();

        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "EUR",
                        "value" => number_format($importo / 100, 2, '.', ''),
                    ],
                    "description" => $descrizione,
                ],
            ],
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancel'),
            ],
        ]);

        foreach ($order['links'] as $link) {
            if ($link['rel'] === 'approve') {
                session([
                    'prenotazione_temp' => $request->only([
                        'data_inizio', 'data_fine', 'numero_stanze',
                        'numero_persone', 'note',
                    ])+ [
                    'prezzo_totale' => $importo, 
                    'confermata_da_id' => auth()->id(),
                ]
                ]);
                return redirect()->away($link['href']);
            }
        }

        return redirect()->back()->with('error', 'Errore nella creazione dell\'ordine PayPal.');
    }

    public function paypalSuccess(Request $request)
    {
        $provider = new PayPalClient(config('services.paypal'));
        $provider->getAccessToken();
        $orderId = $request->get('token') ?? $request->get('orderID');
        $response = $provider->capturePaymentOrder($orderId);

        if ($response['status'] === 'COMPLETED') {
            $captureId = $response['purchase_units'][0]['payments']['captures'][0]['id'];
            $dati = session('prenotazione_temp');
            $prenotazione = Prenotazione::create([
                'data_inizio' => $dati['data_inizio'],
                'data_fine' => $dati['data_fine'],
                'numero_stanze' => $dati['numero_stanze'],
                'numero_persone' => $dati['numero_persone'],
                'note' => $dati['note'] ?? '',
                'stato' => 'confermata',
                'nome' => auth()->user()->nome,
                'email' => auth()->user()->email,
                'telefono' => auth()->user()->telefono,
                'confermata_da_id' => $dati['confermata_da_id'],
                'prezzo_totale' => $dati['prezzo_totale'] / 100,
                'tipo_pagamento' => 'paypal',
                'payment_gateway_id' => $captureId,
            ]);

            Mail::to($prenotazione->email)->send(new \App\Mail\PrenotazioneConfermata($prenotazione));

            // Invio email di notifica all'ADMIN
            try {
                \Mail::to('villettartalemarina@gmail.com')
                    ->send(new \App\Mail\NuovaPrenotazioneAdmin($prenotazione));
            } catch (\Exception $e) {
                \Log::warning('Errore invio email ADMIN: ' . $e->getMessage());
            }

            session()->forget('prenotazione_temp');
            $codicePrenotazione = 'VIL-' . str_pad($prenotazione->id, 4, '0', STR_PAD_LEFT);
            return redirect('/')->with('success', "Pagamento completato con PayPal! Codice: $codicePrenotazione");
        }

        return redirect('/')->with('error', 'Pagamento non completato.');
    }

    public function paypalCancel()
    {
        return redirect('/')->with('error', 'Pagamento PayPal annullato.');
    }

public function success(Request $request)
{
    $sessionId = $request->get('session_id');
    if (!$sessionId) {
        return redirect('/')->with('error', 'Sessione di pagamento non trovata.');
    }

    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    $session = \Stripe\Checkout\Session::retrieve($sessionId);

    if ($session->payment_status !== 'paid') {
        return redirect('/')->with('error', 'Pagamento non completato.');
    }

    $prenotazione = Prenotazione::create([
        'data_inizio' => $session->metadata->data_inizio,
        'data_fine' => $session->metadata->data_fine,
        'numero_stanze' => $session->metadata->numero_stanze,
        'numero_persone' => $session->metadata->numero_persone,
        'note' => $session->metadata->note,
        'stato' => 'confermata',
        'nome' => auth()->user()->nome,
        'email' => auth()->user()->email,
        'telefono' => auth()->user()->telefono,
        'confermata_da_id' => $session->metadata->confermata_da_id,
        'tipo_pagamento' => 'stripe',
        'prezzo_totale' => $session->metadata->prezzo_totale / 100,
        'payment_gateway_id' => $session->payment_intent,
    ]);

    // Invio email di conferma all'UTENTE
    try {
        Mail::to($prenotazione->email)->send(new \App\Mail\PrenotazioneConfermata($prenotazione));
    } catch (\Exception $e) {
        \Log::warning('Errore invio email: ' . $e->getMessage());
    }

    // Invio email di notifica all'ADMIN
    try {
        \Mail::to('villettartalemarina@gmail.com')
            ->send(new \App\Mail\NuovaPrenotazioneAdmin($prenotazione));
    } catch (\Exception $e) {
        \Log::warning('Errore invio email ADMIN: ' . $e->getMessage());
    }

    $codicePrenotazione = 'VIL-' . str_pad($prenotazione->id, 4, '0', STR_PAD_LEFT);
    return redirect('/')->with('success', "Pagamento completato con Stripe! Codice: $codicePrenotazione");
}


    public function cancel()
    {
        // Rimane identico
        $locale = app()->getLocale();
        return view($locale . '.prenotazioni.pagamento_annullato', compact('locale'));
    }
}
