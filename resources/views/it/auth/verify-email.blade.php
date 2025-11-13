@extends($locale . '.layouts.app')

@section('title', 'Verifica la tua Email')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 480px; width: 100%;">
        <h3 class="mb-3 text-center">Verifica la tua Email</h3>
        
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success" role="alert">
                Ti abbiamo inviato un nuovo link di verifica all'indirizzo email fornito.
            </div>
        @endif
        
        <p>
            Prima di procedere, controlla la tua email per il link di verifica.<br>
            Se non hai ricevuto l'email, clicca il bottone qui sotto per inviarla di nuovo.
        </p>
        
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary w-100">Reinvia Email di Verifica</button>
        </form>
    </div>
</div>
@endsection