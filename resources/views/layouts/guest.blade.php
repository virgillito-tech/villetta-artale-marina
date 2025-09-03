<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Accesso - Villetta Artale Marina')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        width: 100%;
        max-width: 450px;
    }
    
    .login-header {
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        color: white;
        padding: 30px;
        text-align: center;
        position: relative;
    }
    
    .login-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="%23ffffff" opacity="0.1"><path d="M0,0v46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1068.95,16,1000,16Z"></path></svg>') repeat-x;
        background-size: 1000px 100px;
    }
    
    .login-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #f1c40f;
    }
    
    .login-body {
        padding: 40px;
    }
    
    .form-control {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }
    
    .btn-login {
        background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
        border: none;
        border-radius: 10px;
        padding: 12px 30px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    }
    
    .login-footer {
        background: #f8f9fa;
        padding: 20px;
        text-align: center;
        border-top: 1px solid #e9ecef;
    }
    
    .back-to-home {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1000;
    }
    
    .back-to-home a {
        color: white;
        text-decoration: none;
        background: rgba(255, 255, 255, 0.2);
        padding: 10px 15px;
        border-radius: 50px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    
    .back-to-home a:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }
    </style>
</head>

<body>
    <!-- Back to Home Button -->
    <div class="back-to-home">
        <a href="{{ route('home') }}">
            <i class="fas fa-arrow-left me-2"></i> Torna al sito
        </a>
    </div>

    <div class="login-container">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
