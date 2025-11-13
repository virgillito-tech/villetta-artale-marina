@extends($locale . '.layouts.app')

@section('title', 'Risultati Ricerca - Villetta Artale Marina')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h3 class="mb-4">Risultati della tua ricerca</h3>

            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Data di arrivo:</strong> {{ $inizio->format('d/m/Y') }}</li>
                <li class="list-group-item"><strong>Data di partenza:</strong> {{ $fine->format('d/m/Y') }}</li>
                <li class="list-group-item"><strong>Numero stanze:</strong> {{ $request->rooms }}</li>
                <li class="list-group-item"><strong>Numero persone:</strong> {{ $request->guests }}</li>
                <li class="list-group-item">
                    <strong>Disponibilità:</strong>
                    @if($isAvailable)
                        <span class="text-success">Disponibile</span>
                    @else
                        <span class="text-danger">Non disponibile</span><br>
                        {{ $message }}
                    @endif
                </li>

                @if($isAvailable)
                    <li class="list-group-item"><strong>Prezzo totale:</strong> €{{ number_format($prezzoTotale, 2, ',', '.') }}</li>
                @endif
            </ul>

            @if($isAvailable)
                {{-- NOTE AGGIUNTIVE --}}
                <div class="mb-3">
                    <label class="form-label">Note aggiuntive</label>
                    <textarea class="form-control" name="note" rows="3" form="checkout-form">{{ old('note') }}</textarea>
                </div>

                {{-- 🔹 STRIPE PAYMENT BUTTON (Apple Pay / Google Pay / Carte) --}}
                <h5 class="mt-4 mb-3">Paga con carta o wallet digitale (Stripe)</h5>
                <form id="checkout-form" method="POST" action="{{ route('pagamento.checkout') }}">
                    @csrf
                    <input type="hidden" name="data_inizio" value="{{ $request->data_inizio }}">
                    <input type="hidden" name="data_fine" value="{{ $request->data_fine }}">
                    <input type="hidden" name="numero_stanze" value="{{ $request->rooms }}">
                    <input type="hidden" name="numero_persone" value="{{ $request->guests }}">
                    <input type="hidden" name="prezzo_totale" value="{{ $prezzoTotale * 100 }}">{{-- Stripe vuole centesimi --}}
                    <input type="hidden" name="tipo_pagamento" value="stripe">

                    <div id="payment-request-button" class="mb-3"></div>
                    <!-- <div class="d-grid"> -->
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fab fa-cc-stripe me-2"></i> Paga con Stripe
                        </button>
                    <!-- </div> -->
                </form>

                <script src="https://js.stripe.com/v3/"></script>
                <script>
                    const stripe = Stripe('{{ config('services.stripe.key') }}');
                    const paymentRequest = stripe.paymentRequest({
                        country: 'IT',
                        currency: 'eur',
                        total: {
                            label: 'Prenotazione Villetta',
                            amount: {{ $prezzoTotale * 100 }},
                        },
                        requestPayerName: true,
                        requestPayerEmail: true,
                    });

                    const elements = stripe.elements();
                    const prButton = elements.create('paymentRequestButton', { paymentRequest });

                    paymentRequest.canMakePayment().then(result => {
                        if (result) prButton.mount('#payment-request-button');
                        else document.getElementById('payment-request-button').style.display = 'none';
                    });
                </script>

                <hr class="my-4">

                {{-- 🔹 PAYPAL SMART BUTTONS (ufficiale) --}}
                <h5 class="mb-3">Oppure paga con PayPal</h5>
                <form id="paypal-checkout-form" method="POST" action="{{ route('pagamento.checkout') }}">
                    @csrf
                    <input type="hidden" name="data_inizio" value="{{ $request->data_inizio }}">
                    <input type="hidden" name="data_fine" value="{{ $request->data_fine }}">
                    <input type="hidden" name="numero_stanze" value="{{ $request->rooms }}">
                    <input type="hidden" name="numero_persone" value="{{ $request->guests }}">
                    <input type="hidden" name="prezzo_totale" value="{{ $prezzoTotale * 100 }}">
                    <input type="hidden" name="tipo_pagamento" value="paypal">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fab fa-paypal me-2"></i> Paga con PayPal
                    </button>
                </form>
            @else
                <a href="{{ url('/') }}" class="btn btn-secondary">Torna alla ricerca</a>
            @endif

        </div>
    </div>
</div>
@endsection
