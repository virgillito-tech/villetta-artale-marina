@extends($locale . '.layouts.app')

@section('title', 'Le Mie Prenotazioni - Villetta Artale Marina')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Le Mie Prenotazioni</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($prenotazioni->isEmpty())
        <div class="alert alert-info">
            Non hai ancora effettuato prenotazioni.
        </div>
    @else
        <div class="row">
            @foreach($prenotazioni as $prenotazione)
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <i class="fas fa-calendar-check text-primary me-2"></i>
                                Dal {{ \Carbon\Carbon::parse($prenotazione->data_inizio)->format('d/m/Y') }}
                                al {{ \Carbon\Carbon::parse($prenotazione->data_fine)->format('d/m/Y') }}
                            </h5>
                            
                            <ul class="list-unstyled text-body-secondary mb-3">
                                 <li><i class="fas fa-home fa-fw me-2"></i>Prenotazione: {{ $prenotazione->codice_prenotazione }}</li>
                                <li><i class="fas fa-users fa-fw me-2"></i> Persone: {{ $prenotazione->numero_persone }}</li>
                                <li><i class="fas fa-door-open fa-fw me-2"></i> Stanze: {{ $prenotazione->numero_stanze }}</li>
                                <li><i class="fas fa-euro-sign fa-fw me-2"></i> Prezzo Totale: €{{ number_format($prenotazione->prezzo_totale, 2, ',', '.') }}</li>
                                <li><i class="fas fa-credit-card fa-fw me-2"></i> Metodo di Pagamento: {{ $prenotazione->tipo_pagamento }}</li>
                            </ul>

                            @if($prenotazione->note)
                                <p class="mt-2 fst-italic"><i class="fas fa-sticky-note me-2 text-muted"></i> {{ $prenotazione->note }}</p>
                            @endif

                            <div class="mt-auto">
                                <p class="mb-2"><i class="fas fa-info-circle fa-fw me-2"></i> Stato: 
                                    <span class="badge 
                                        @if($prenotazione->stato == 'confermata') text-bg-success
                                        @elseif($prenotazione->stato == 'in attesa') text-bg-warning
                                        @else text-bg-danger @endif">
                                        {{ ucfirst($prenotazione->stato) }}
                                    </span>
                                </p>

                                @php
                                    $dataInizio = \Carbon\Carbon::parse($prenotazione->data_inizio);
                                    $oggi = now()->startOfDay();
                                    // Calcola i giorni mancanti; se negativo, la data è passata
                                    $giorniMancanti = $oggi->diffInDays($dataInizio, false);
                                @endphp

                                @if($prenotazione->stato === 'confermata')
                                    
                                    @if($giorniMancanti > 30)
                                        <form action="{{ route('prenotazioni.annulla', $prenotazione->id) }}" method="POST" 
                                            onsubmit="return confirm('Sei sicuro di voler annullare questa prenotazione? L\'importo ti sarà rimborsato.');">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm mt-2">
                                                <i class="fas fa-times me-2"></i> Annulla e Chiedi Rimborso
                                            </button>
                                        </form>
                                    @elseif($giorniMancanti > 0)
                                        <p class="text-muted small mt-2">Non è possibile annullare online (meno di 30 giorni all'arrivo).</p>
                                    @else
                                        <p class="text-muted small mt-2">Prenotazione conclusa.</p>
                                    @endif

                                @elseif($prenotazione->stato === 'annullata')
                                    <p class="text-danger small mt-2">Prenotazione annullata.</p>
                                @endif
                            </div>
                            
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection