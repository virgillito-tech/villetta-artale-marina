@extends($locale . '.layouts.admin')

@section('title', 'Tutte le Prenotazioni - Villetta Artale Marina')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Panoramica</a></li>
    <li class="breadcrumb-item active" aria-current="page">Gestione Prenotazioni</li>
@endsection

@section('content')

{{-- 1. Card per Filtri e Ricerca --}}
<div class="card admin-card mb-4">
    <div class="card-header bg-light">
        <h4 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i> Filtra e Cerca
        </h4>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.prenotazioni.index') }}">
            <div class="row g-3">
                {{-- Campo Ricerca --}}
                <div class="col-lg-12">
                    <label for="search" class="form-label">Cerca per Codice, Nome o Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Es: VIL-0004, Mario Rossi, email@esempio.com" 
                               value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Filtro Stato --}}
                <div class="col-lg-4 col-md-6">
                    <label for="stato" class="form-label">Stato</label>
                    <select class="form-select" id="stato" name="stato">
                        <option value="">Tutti gli stati</option>
                        <option value="confermata" @selected(request('stato') == 'confermata')>Confermata</option>
                        <option value="in attesa" @selected(request('stato') == 'in attesa')>In Attesa</option>
                        <option value="annullata" @selected(request('stato') == 'annullata')>Annullata</option>
                    </select>
                </div>

                {{-- Filtro Metodo Pagamento --}}
                <div class="col-lg-4 col-md-6">
                    <label for="tipo_pagamento" class="form-label">Metodo Pagamento</label>
                    <select class="form-select" id="tipo_pagamento" name="tipo_pagamento">
                        <option value="">Tutti i metodi</option>
                        <option value="stripe" @selected(request('tipo_pagamento') == 'stripe')>Stripe</option>
                        <option value="paypal" @selected(request('tipo_pagamento') == 'paypal')>PayPal</option>
                    </select>
                </div>

                {{-- Filtro Numero Persone --}}
                <div class="col-lg-4 col-md-6">
                    <label for="numero_persone" class="form-label">Numero Persone</label>
                    <select class="form-select" id="numero_persone" name="numero_persone">
                        <option value="">Qualsiasi</option>
                        @for ($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" @selected(request('numero_persone') == $i)>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            
            {{-- Pulsanti Form --}}
            <div class="text-end mt-3">
                <a href="{{ route('admin.prenotazioni.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Resetta Filtri
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-1"></i> Applica
                </button>
            </div>
        </form>
    </div>
</div>

{{-- 2. Card per Tabella Risultati --}}
<div class="card admin-card">
    <div class="card-header bg-primary text-white">
        <h4 class="card-title mb-0">
            <i class="fas fa-calendar-alt me-2"></i> Risultati Prenotazioni
        </h4>
    </div>
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Codice</th>
                        <th>Cliente</th>
                        <th>Date</th>
                        <th>Dettagli</th>
                        <th>Prezzo</th>
                        <th>Stato</th>
                        <th>Pagamento</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prenotazioni as $prenotazione)
                    <tr>
                        {{-- Codice --}}
                        <td>
                            <span class="badge bg-secondary">{{ $prenotazione->codice_prenotazione }}</span>
                        </td>
                        
                        {{-- Cliente --}}
                        <td>
                            <div>
                                <strong>{{ $prenotazione->nome }}</strong><br>
                                <small class="text-muted">{{ $prenotazione->email }}</small><br>
                                <small class="text-muted">{{ $prenotazione->telefono }}</small>
                            </div>
                        </td>

                        {{-- Date --}}
                        <td>
                            <small>
                                <i class="fas fa-calendar-check text-success me-1"></i>
                                {{ \Carbon\Carbon::parse($prenotazione->data_inizio)->format('d/m/Y') }}
                                <br>
                                <i class="fas fa-calendar-times text-danger me-1"></i>
                                {{ \Carbon\Carbon::parse($prenotazione->data_fine)->format('d/m/Y') }}
                            </small>
                        </td>

                        {{-- Dettagli (Persone/Stanze) --}}
                        <td>
                            <span class="badge bg-info text-dark">
                                <i class="fas fa-users me-1"></i> {{ $prenotazione->numero_persone }} Pers.
                            </span>
                            <br>
                            <span class="badge bg-warning text-dark mt-1">
                                <i class="fas fa-door-open me-1"></i> {{ $prenotazione->numero_stanze }} Stanze
                            </span>
                        </td>

                        {{-- Prezzo --}}
                        <td>
                            <strong>€{{ number_format($prenotazione->prezzo_totale, 2, ',', '.') }}</strong>
                        </td>

                        {{-- Stato --}}
                        <td>
                            @switch($prenotazione->stato)
                                @case('confermata')
                                    <span class="badge text-bg-success"><i class="fas fa-check me-1"></i>Confermata</span>
                                    @break
                                @case('in attesa')
                                    <span class="badge text-bg-warning"><i class="fas fa-clock me-1"></i>In Attesa</span>
                                    @break
                                @case('annullata')
                                    <span class="badge text-bg-danger"><i class="fas fa-times me-1"></i>Annullata</span>
                                    @break
                            @endswitch
                        </td>

                        {{-- Pagamento --}}
                        <td>
                            <span class="badge text-bg-dark">{{ ucfirst($prenotazione->tipo_pagamento) }}</span><br>
                            <small class="text-muted" title="{{ $prenotazione->payment_gateway_id }}">
                                ID: ...{{ substr($prenotazione->payment_gateway_id, -6) }}
                            </small>
                        </td>
                        <td>
                            @if($prenotazione->stato !== 'annullata')
                                <form action="{{ route('admin.prenotazioni.destroy', $prenotazione->id) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler annullare questa prenotazione? L\'azione non può essere annullata.');">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <div class="mb-2">
                                        <button type="submit" class="btn btn-danger btn-sm w-100" title="Annulla questa prenotazione">
                                            <i class="fas fa-times"></i> Annulla
                                        </button>
                                    </div>

                                    {{-- Mostra la casella di rimborso SOLO se è confermata E ha un ID pagamento --}}
                                    @if($prenotazione->stato === 'confermata' && $prenotazione->payment_gateway_id)
                                    <div class="form-check form-switch" title="Spunta per emettere un rimborso completo al cliente.">
                                        <input class="form-check-input" type="checkbox" name="with_refund" id="refund-{{ $prenotazione->id }}">
                                        <label class="form-check-label small" for="refund-{{ $prenotazione->id }}">
                                            Emetti Rimborso
                                        </label>
                                    </div>
                                    @endif
                                </form>
                            @else
                                {{-- Già annullata, non mostrare nulla --}}
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i><br>
                            <h5>Nessuna prenotazione trovata</h5>
                            <p>Prova a modificare i filtri o il termine di ricerca.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Link Paginazione --}}
        <div class="mt-3">
            {{ $prenotazioni->links() }}
        </div>
    </div>
</div>

@endsection