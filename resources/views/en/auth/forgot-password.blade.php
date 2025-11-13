@extends($locale . '.layouts.app')

@section('title', 'Password Dimenticata')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
  <div style="max-width: 400px; width: 100%;">

    <h2 class="mb-4 text-center">Lost Password</h2>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" required autofocus>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
    </form>
  </div>
</div>
@endsection
