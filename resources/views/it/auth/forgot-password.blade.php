@extends($locale . '.layouts.app')

@section('title', 'Password Dimenticata')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
  <div style="max-width: 400px; width: 100%;">

    <h2 class="mb-4 text-center">Password Dimenticata</h2>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">Indirizzo Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" required autofocus>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <button type="submit" class="btn btn-primary w-100">Invia Link di Reset</button>
    </form>
  </div>
</div>
@endsection
