@extends($locale . '.layouts.app')

@section('title', 'Modifica Password - Villetta Artale Marina')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="login-card position-relative" style="overflow: hidden; max-width: 500px; width:100%;">

    <!-- Sfondo trasparente con logo -->
    <div style="
        position:absolute; 
        top:0; left:0; 
        width:100%; height:100%; 
        background:url('{{ asset('images/logo.jpg') }}') center center no-repeat; 
        background-size:300px; 
        opacity:0.05; 
        z-index:0;
        filter: blur(1px);
    "></div>

    <!-- Contenuto Card -->
    <div style="position: relative; z-index: 1;">
        <!-- Header -->
        <div class="login-header text-center">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo del Sito" class="mb-3" style="max-width: 120px;">
            <h2 class="mb-2">
                <i class="fas fa-lock me-2"></i>
                Edit Password
            </h2>
            <p class="mb-0">Update your account credentials</p>
        </div>

        <!-- Body -->
        <div class="login-body">
            <!-- Alert di successo -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Alert di errore -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Validazione -->
            @if($errors->any())
                <div class="alert alert-danger mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Error!</strong> Check the data entered.
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                @method('PUT')

                <!-- Password Attuale -->
                <div class="mb-3">
                    <label for="current_password" class="form-label">
                        <i class="fas fa-key me-2 text-primary"></i>Current password
                    </label>
                    <input type="password" name="current_password" id="current_password" 
                           class="form-control @error('current_password') is-invalid @enderror" required>
                    @error('current_password')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Nuova Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2 text-primary"></i>New Password
                    </label>
                    <input type="password" name="password" id="password" 
                           class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Conferma Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-check me-2 text-primary"></i>Confirm new Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>

                <!-- Submit Button -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-save me-2"></i>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer Card -->
    <div class="text-center py-3" style="background: #f8f9fa; border-top: 1px solid #e9ecef; position: relative; z-index: 1;">
        <small class="text-muted">
            <i class="fas fa-home me-1"></i>
            <a href="{{ route('user.dashboard') }}" class="text-decoration-none">Return to Dashboard</a>
        </small>
    </div>
</div>
</div>
@endsection
