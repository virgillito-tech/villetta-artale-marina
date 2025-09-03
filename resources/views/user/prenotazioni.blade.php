@extends('layouts.app')

@section('title', 'Le Mie Prenotazioni - Villetta Artale Marina')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Le Mie Prenotazioni</h2>

    @if($prenotazioni->isEmpty())
        <div class="alert alert-info">
            Non hai ancora effettuato prenotazioni.
        </div>
    @else
        <div class="row">
            @foreach($prenotazioni as $prenotazione)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-calendar-check text-primary me-2"></i>
                                Dal {{ \Carbon\Carbon::parse($prenotazione->data_inizio)->format('d/m/Y') }}
                                al {{ \Carbon\Carbon::parse($prenotazione->data_fine)->format('d/m/Y') }}
                            </h5>
                            <p class="mb-1"><i class="fas fa-users me-2 text-success"></i> Persone: {{ $prenotazione->numero_persone }}</p>
                            <p class="mb-1"><i class="fas fa-door-open me-2 text-warning"></i> Stanze: {{ $prenotazione->numero_stanze }}</p>
                            <p class="mb-1"><i class="fas fa-info-circle me-2 text-secondary"></i> Stato: 
                                <span class="badge 
                                    @if($prenotazione->stato == 'confermata') bg-success
                                    @elseif($prenotazione->stato == 'in attesa') bg-warning
                                    @else bg-danger @endif">
                                    {{ ucfirst($prenotazione->stato) }}
                                </span>
                            </p>
                            @if($prenotazione->note)
                                <p class="mt-2"><i class="fas fa-sticky-note me-2 text-muted"></i> {{ $prenotazione->note }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
