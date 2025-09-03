@extends('layouts.app')

@section('title', 'Recensioni - Villetta Artale Marina')

@section('content')
@if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@php
    // Se $recensioni non è definito, usa una collezione vuota
    $recensioni = $recensioni ?? collect();
@endphp

<div class="container my-5">
    <h2 class="mb-4 text-center">Lascia una recensione</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <form action="{{ route('recensioni.store') }}" method="POST" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" id="nome" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}" required>
                    @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="contenuto" class="form-label">Recensione</label>
                    <textarea id="contenuto" name="contenuto" rows="4" class="form-control @error('contenuto') is-invalid @enderror" required>{{ old('contenuto') }}</textarea>
                    @error('contenuto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label for="voto" class="form-label">Voto (1-5)</label>
                    <select id="voto" name="voto" class="form-select @error('voto') is-invalid @enderror" required>
                        <option value="" disabled selected>Seleziona un voto</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('voto') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    @error('voto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-send-fill"></i> Invia Recensione
                </button>
            </form>
        </div>
    </div>
</div>

    <hr>

<div class="container my-4">
    <h3 class="mb-4 text-center">Recensioni ricevute</h3>

    @forelse($recensioni as $recensione)
        <div 
            class="review-card list-group-item d-flex flex-column flex-sm-row align-items-sm-center py-3 px-4 mb-3 rounded shadow-sm"
            role="button"
            data-bs-toggle="modal"
            data-bs-target="#reviewModal{{ $recensione->id }}"
        >
            <div class="me-sm-4 flex-shrink-0 text-center" style="min-width: 130px;">
                <strong class="d-block">{{ $recensione->nome }}</strong>
                <small class="text-warning fs-5">
                    {!! str_repeat('&#9733;', $recensione->voto) !!}
                    {!! str_repeat('&#9734;', 5 - $recensione->voto) !!}
                </small>
                <small class="d-block text-muted">{{ $recensione->created_at->format('d/m/Y') }}</small>
            </div>

            <div class="flex-grow-1 text-truncate" style="max-width: 100%;">
                <p class="mb-0 review-preview">
                    {{ Str::limit($recensione->contenuto, 120, '...') }}
                </p>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="reviewModal{{ $recensione->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $recensione->id }}" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel{{ $recensione->id }}">{{ $recensione->nome }} - Recensione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
              </div>
              <div class="modal-body">
                <p><strong>Voto:</strong> 
                    <span class="text-warning fs-5">
                        {!! str_repeat('&#9733;', $recensione->voto) !!}
                        {!! str_repeat('&#9734;', 5 - $recensione->voto) !!}
                    </span>
                </p>
                <p>{{ $recensione->contenuto }}</p>
                <small class="text-muted">{{ $recensione->created_at->format('d/m/Y H:i') }}</small>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
              </div>
            </div>
          </div>
        </div>
    @empty
        <p class="text-center text-muted">Nessuna recensione ancora.</p>
    @endforelse
</div>

<style>
.review-card {
    background-color: #fff;
    cursor: pointer;
    transition: box-shadow 0.3s ease, transform 0.3s ease;
    border: 1px solid #ddd;
}
.review-card:hover {
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    transform: translateY(-4px);
}
.review-preview {
    display: -webkit-box;
    -webkit-line-clamp: 3; /* Numero di righe visibili */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
}
</style>
@endsection