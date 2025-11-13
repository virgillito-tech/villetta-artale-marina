@extends($locale . '.layouts.app')

@section('title', 'Verifica la tua Email')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 480px; width: 100%;">
        <h3 class="mb-3 text-center">Verify your Email</h3>
        
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success" role="alert">
                We've sent you a new verification link to the email address you provided.
            </div>
        @endif
        
        <p>
            Before proceeding, please check your email for the verification link..<br>
            If you haven't received the email, click the button below to resend it..
        </p>
        
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary w-100">Resend Verification Email</button>
        </form>
    </div>
</div>
@endsection