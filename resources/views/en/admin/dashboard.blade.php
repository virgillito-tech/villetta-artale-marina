@extends($locale . '.layouts.admin')

@section('title', 'Dashboard - Villetta Artale Marina')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Overview</li>
@endsection

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-body text-center py-4">
                <h2 class="text-primary mb-2">
                    <i class="fas fa-wave-square me-2"></i>
                    Welcome, {{ Auth::user()->nome }}!
                </h2>
                <p class="text-muted mb-0">Here's an overview of your business</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon bg-success text-white me-3">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h3 class="h2 mb-0 text-success">{{ \App\Models\Prenotazione::where('stato', 'confermata')->count() }}</h3>
                    <small class="text-muted">Prenotazioni Confermate</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon bg-warning text-white me-3">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h3 class="h2 mb-0 text-warning">{{ \App\Models\Prenotazione::where('stato', 'in attesa')->count() }}</h3>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon bg-info text-white me-3">
                    <i class="fas fa-euro-sign"></i>
                </div>
                <div>
                    <h3 class="h2 mb-0 text-info">€2,450</h3>
                    <small class="text-muted">Incassi del Mese</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon bg-primary text-white me-3">
                    <i class="fas fa-percentage"></i>
                </div>
                <div>
                    <h3 class="h2 mb-0 text-primary">78%</h3>
                    <small class="text-muted">Tasso di Occupazione</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card admin-card">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('admin.prenotazioni.index') }}" class="btn btn-outline-primary w-100 py-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-calendar-alt fa-2x me-3"></i>
                                <div class="text-start">
                                    <strong>Manage Reservations</strong><br>
                                    <small>View and edit</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-success w-100 py-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-eye fa-2x me-3"></i>
                                <div class="text-start">
                                    <strong>Vedi Sito Pubblico</strong><br>
                                    <small>Open in a new window</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <button class="btn btn-outline-info w-100 py-3" onclick="alert('Prossimamente!')">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-chart-bar fa-2x me-3"></i>
                                <div class="text-start">
                                    <strong>Detailed Report</strong><br>
                                    <small>Advanced Statistics</small>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="row">
    <div class="col-12">
        <div class="card admin-card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    <i class="fas fa-history me-2 text-primary"></i>Recent Bookings
                </h4>
                <a href="{{ route('admin.prenotazioni.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-arrow-right me-1"></i>View All
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>People</th>
                                <th>State</th>
                                <th>Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $prenotazioni_recenti = \App\Models\Prenotazione::latest()->take(5)->get();
                            @endphp
                            @forelse($prenotazioni_recenti as $prenotazione)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">VIL-{{ str_pad($prenotazione->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $prenotazione->nome }}</strong><br>
                                        <small class="text-muted">{{ $prenotazione->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <small>
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($prenotazione->data_inizio)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($prenotazione->data_fine)->format('d/m/Y') }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="fas fa-users me-1"></i>{{ $prenotazione->numero_persone }}
                                    </span>
                                </td>
                                <td>
                                    @switch($prenotazione->stato)
                                        @case('confermata')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Confirmed
                                            </span>
                                            @break
                                        @case('in attesa')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                            @break
                                        @case('annullata')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Cancelled
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $prenotazione->stato }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <!-- <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Visualizza" onclick="alert('Dettagli prenotazione #{{ $prenotazione->id }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($prenotazione->stato === 'in attesa')
                                            <button class="btn btn-outline-success" title="Conferma" onclick="alert('Conferma prenotazione #{{ $prenotazione->id }}')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                    </div> -->
                                    <span class="badge bg-secondary">{{ $prenotazione->tipo_pagamento }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    <h5>No reservations found</h5>
                                    <p>New bookings will appear here</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
