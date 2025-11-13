<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin - Villetta Artale Marina')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Glightbox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <style>
    /* Stili minimali per distinguere l'area admin */
    .admin-badge {
        background: linear-gradient(45deg, #ff6b6b, #ffa500);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .admin-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .admin-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="%23ffffff" opacity="0.1"><path d="M0,0v46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1068.95,16,1000,16Z"></path></svg>') repeat-x;
        background-size: 1000px 100px;
    }
    
    .stats-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .breadcrumb-admin {
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        padding: 12px 20px;
        border: 1px solid rgba(0,0,0,0.1);
    }
    
    .admin-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        transition: box-shadow 0.2s ease;
    }
    
    .admin-card:hover {
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    /*
* Stile per il logo rotondo nell'header
*/
.logo-header-round {
    width: 50px;           /* ⬅️ Imposta la larghezza che desideri */
    height: 50px;          /* ⬅️ L'altezza DEVE essere identica alla larghezza */
    border-radius: 50%;  /* ⬅️ Questo è il comando che lo rende rotondo! */
    object-fit: cover;   /* ⬅️ Impedisce all'immagine di deformarsi/schiacciarsi */
}
    </style>
</head>

<body>
    <!-- Navbar (uguale al sito ma con indicazione admin) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                Villetta Artale Marina
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="logo-header-round" style="max-height: 60px;">
                <span class="admin-badge ms-2">ADMIN</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Menu Admin -->
                <ul class="navbar-nav ms-auto me-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.prenotazioni.*') ? 'active' : '' }}" 
                          href="{{ url('/admin/calendario') }}">
                            <i class="fas fa-calendar-alt me-1"></i> Calendario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/prezzi') }}" >
                            <i class="fas fa-euro-sign me-1"></i> Prezzi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="alert('Prossimamente!')">
                            <i class="fas fa-chart-bar me-1"></i> Statistiche
                        </a>
                    </li>
                </ul>
                
                <!-- Link al sito pubblico -->
                <div class="me-3">
                    <a class="btn btn-outline-light btn-sm" href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-external-link-alt me-1"></i> Vedi Sito
                    </a>
                </div>

                <!-- User dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-crown text-warning me-2"></i>
                        {{ Auth::user()->username }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <h6 class="dropdown-header">
                                <i class="fas fa-crown text-warning me-1"></i> 
                                Amministratore
                            </h6>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-edit me-2"></i> Profilo
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

                 <div class="language-switcher">
        <a href="{{ route('lang.switch', 'it') }}" class="me-2">🇮🇹</a>
        <a href="{{ route('lang.switch', 'en') }}">🇺🇸</a>
    </div>
            </div>
        </div>
    </nav>

    <!-- Header Admin (simile al sito ma con stile admin) -->
    <header class="admin-header text-white text-center py-4">
        <div class="container position-relative">
            <h1><i class="fas fa-shield-alt me-2"></i>Area Amministratore</h1>
            <p class="lead mb-0">Gestione Villetta Artale Marina</p>
        </div>
    </header>

    <!-- Breadcrumb -->
    <div class="container my-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-admin mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                @yield('breadcrumbs')
            </ol>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container my-4">
        <!-- Messaggi di sistema -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Successo!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Errore!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Attenzione!</strong> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Contenuto principale -->
        @yield('content')
    </div>

    <!-- Footer (uguale al sito) -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            &copy; {{ date('Y') }} Villetta Artale Marina - 
            <span class="text-warning">
                <i class="fas fa-shield-alt me-1"></i>Area Amministratore
            </span>
        </div>
    </footer>

    <!-- Scripts (uguali al sito) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    
    <!-- FullCalendar JS per pagine che ne hanno bisogno -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/it.global.min.js'></script>
    
    <!-- Chart.js per statistiche -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Scripts personalizzati -->
    <script>
        // Auto-close alerts dopo 5 secondi
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Conferma azioni pericolose
        document.querySelectorAll('.btn-danger[data-confirm]').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                if (!confirm(this.dataset.confirm)) {
                    e.preventDefault();
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
