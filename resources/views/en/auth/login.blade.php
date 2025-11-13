@extends($locale . '.layouts.app')
@section('title', 'Accedi - Villetta Artale Marina')

@section('content')
<div class="login-card position-relative" style="overflow: hidden;">
    
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

    <!-- Contenuto Login -->
    <div style="position: relative; z-index: 1;">
        <!-- Header -->
        <div class="login-header text-center">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo del Sito" class="mb-3" style="max-width: 120px;">
            <h2 class="mb-2">
                <i class="fas fa-umbrella-beach me-2"></i>
                Reservation Area
            </h2>
            <p class="mb-0">Login to your Account</p>
        </div>

        <!-- Body -->
        <div class="login-body">
            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Error!</strong> Check the data entered.
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Login Field (Email or Username) -->
                <div class="mb-3">
                    <label for="login" class="form-label">
                        <i class="fas fa-user me-2 text-primary"></i>Email or Username
                    </label>
                    <input id="login" 
                           type="text" 
                           class="form-control @error('login') is-invalid @enderror" 
                           name="login" 
                           value="{{ old('login') }}" 
                           required 
                           autofocus 
                           placeholder="Insert email o username">
                    @error('login')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2 text-primary"></i>Password
                    </label>
                    <div class="position-relative">
                        <input id="password" 
                               type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" 
                               required 
                               autocomplete="current-password" 
                               placeholder="Insert password">
                        <button type="button" 
                                class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3" 
                                id="togglePassword" 
                                style="z-index: 10; border: none; background: none;">
                            <i class="fas fa-eye text-muted"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-4">
                    <input type="checkbox" 
                           class="form-check-input" 
                           name="remember" 
                           id="remember" 
                           {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        <i class="fas fa-heart me-1 text-danger"></i>Remember me
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        <span id="loginText">Login</span>
                    </button>
                </div>

                <!-- Links -->
                <div class="row text-center">
                    @if (Route::has('password.request'))
                        <div class="col-md-6">
                            <a href="{{ route('password.request') }}" class="text-decoration-none">
                                <i class="fas fa-question-circle me-1"></i>Forgot your password?
                            </a>
                        </div>
                    @endif
                    
                    @if (Route::has('register'))
                        <div class="col-md-6">
                            <a href="{{ route('register') }}" class="text-decoration-none">
                                <i class="fas fa-user-plus me-1"></i>Sign In
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Footer Card -->
    <div class="text-center py-3" style="background: #f8f9fa; border-top: 1px solid #e9ecef; position: relative; z-index: 1;">
        <small class="text-muted">
            <i class="fas fa-home me-1"></i>
            <a href="{{ route('home') }}" class="text-decoration-none">Return to site</a>
        </small>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }
    
    // Form submission with loading state
    const form = document.getElementById('loginForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const loginText = document.getElementById('loginText');
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        loginText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Accesso in corso...';
        
        // Re-enable button after 5 seconds (in case of errors)
        setTimeout(function() {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                loginText.innerHTML = 'Accedi';
            }
        }, 5000);
    });
    
    // Animazione focus sui campi
    document.querySelectorAll('.form-control').forEach(function(input) {
        input.addEventListener('focus', function() {
            this.parentElement.querySelector('.form-label i').style.color = '#0d6efd';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.querySelector('.form-label i').style.color = '';
        });
    });
});
</script>
@endpush
