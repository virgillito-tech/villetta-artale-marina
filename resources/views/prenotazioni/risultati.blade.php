@extends('layouts.app')

@section('title', 'Risultati Ricerca - Villetta Artale Marina')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h3>Risultati della tua ricerca</h3>

            <ul class="list-group mb-3">
                <li class="list-group-item">
                    <strong>Data di arrivo:</strong> {{ $inizio->format('d/m/Y') }}
                </li>
                <li class="list-group-item">
                    <strong>Data di partenza:</strong> {{ $fine->format('d/m/Y') }}
                </li>
                <li class="list-group-item">
                    <strong>Numero stanze:</strong> {{ $request->rooms }}
                </li>
                <li class="list-group-item">
                    <strong>Numero persone:</strong> {{ $request->guests }}
                </li>
                <li class="list-group-item">
                    <strong>Disponibilità:</strong>
                    @if($isAvailable)
                        <span class="text-success">Disponibile</span>
                    @else
                        <span class="text-danger">Non disponibile</span>
                        <br>{{ $message }}
                    @endif
                </li>
                @if($isAvailable)
                <li class="list-group-item">
                    <strong>Prezzo totale:</strong> €{{ number_format($prezzoTotale, 2, ',', '.') }}
                </li>
                @endif
            </ul>

            @if($isAvailable)
            {{-- Form pagamento --}}
            <form method="POST" action="{{ route('pagamento.checkout') }}">
                @csrf
                <input type="hidden" name="data_inizio" value="{{ $request->data_inizio }}">
                <input type="hidden" name="data_fine" value="{{ $request->data_fine }}">
                <input type="hidden" name="numero_stanze" value="{{ $request->rooms }}">
                <input type="hidden" name="numero_persone" value="{{ $request->guests }}">
                <input type="hidden" name="note" value="{{ old('note') }}">
                <input type="hidden" name="importo" value="{{ $prezzoTotale * 100 }}"> {{-- Stripe vuole i centesimi --}}

                <div class="mb-3">
                    <label class="form-label">Note aggiuntive</label>
                    <textarea class="form-control" name="note" rows="3">{{ old('note') }}</textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fab fa-cc-stripe me-2"></i> Conferma & Paga
                    </button>
                    <a href="{{ url('/') }}" class="btn btn-secondary">Torna alla ricerca</a>
                </div>
            </form>

            {{-- Stripe Payment Request Button --}}
            <div id="payment-request-button" class="mt-4"></div>
            <script src="https://js.stripe.com/v3/"></script>
            <script>
                const stripe = Stripe('{{ config('services.stripe.key') }}');

                const paymentRequest = stripe.paymentRequest({
                    country: 'IT',
                    currency: 'eur',
                    total: { 
                        label: 'Prenotazione Villetta', 
                        amount: {{ $isAvailable ? $prezzoTotale * 100 : 0 }} 
                    },
                    requestPayerName: true,
                    requestPayerEmail: true,
                });

                const elements = stripe.elements();
                const prButton = elements.create('paymentRequestButton', { paymentRequest });

                paymentRequest.canMakePayment().then(function(result) {
                    if (result) {
                        prButton.mount('#payment-request-button');
                    } else {
                        document.getElementById('payment-request-button').style.display = 'none';
                    }
                });
            </script>
            @else
                <a href="{{ url('/') }}" class="btn btn-secondary">Torna alla ricerca</a>
            @endif

        </div>
    </div>
</div>
@endsection
