<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Villetta Artale Marina')</title>
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
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        .flatpickr-calendar { z-index: 9999 !important; }
        .dropdown-menu {
            max-width: 100vw;
            width: auto;
            min-width: 200px; 
        }

        .language-switcher {
    margin-left: 15px; /* spazio tra user e lingue */
}

        
        /* Stile per la pagina di login */
        .login-container {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 0;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }
        
        .login-header {
            background: var(--bs-primary);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-body {
            padding: 40px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .btn-login {
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        /* Navbar default */
.navbar {
    transition: all 0.3s ease;
    padding: 20px 0;
}

/* Navbar ridotta quando scrolli */
.navbar.shrink {
    padding: 5px 0;
    background-color: rgba(0, 0, 0, 0.9); /* più scura */
}

.navbar.shrink .navbar-brand img {
    max-height: 35px !important; /* riduce il logo */
    transition: max-height 0.3s ease;
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
        <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <!-- Logo a sinistra -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="logo-header-round" style="max-height: 50px;">
            Villetta Artale Marina
        </a>

        <!-- Toggler per mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">

            <!-- Menu centrale -->
            <ul class="navbar-nav mx-auto text-center">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/galleria') }}">Galleria</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/servizi') }}">Servizi</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/recensioni') }}">Recensioni</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/contatti') }}">Contatti</a></li>
            </ul>

            <!-- Dropdown Prenotazione + pulsanti social -->
            <div class="d-flex align-items-center ms-auto flex-wrap">
                <!-- Dropdown Prenotazione -->
                @if(!request()->routeIs('login', 'register'))
                <div class="dropdown me-3 mb-2 mb-lg-0">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="bookingDropdown" 
                            data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false">
                        Prenotazione
                    </button>
                    <form class="dropdown-menu p-4" style="min-width: 320px;" aria-labelledby="bookingDropdown" 
                        id="bookingForm" method="GET" action="{{ route('prenotazioni.search') }}">
                            <div class="mb-3">
                                <label for="daterange" class="form-label">Check-in/Check-out</label>
                                <input type="text" id="daterange" name="daterange" class="form-control" placeholder="Seleziona date" required autocomplete="off">
                                <input type="hidden" id="data_inizio" name="data_inizio">
                                <input type="hidden" id="data_fine" name="data_fine">
                            </div>
                            <div class="mb-3">
                                <label for="rooms" class="form-label">Numero Stanze</label>
                                <select class="form-select" id="rooms" name="rooms" required>
                                    @for ($i = 1; $i <= 3; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="guests" class="form-label">Numero Persone</label>
                                <select class="form-select" id="guests" name="guests" required>
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Cerca</button>
                        </form>
                    </div>
                @endif

                <!-- Icone social -->
                <div class="d-flex align-items-center me-3 mb-2 mb-lg-0">
                    <a href="https://wa.me/393454218671" class="btn btn-outline-light btn-sm rounded-circle mx-1" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://www.instagram.com/villetta_artale_marina" class="btn btn-outline-light btn-sm rounded-circle mx-1" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://t.me/virgillito" class="btn btn-outline-light btn-sm rounded-circle mx-1" aria-label="Telegram"><i class="fab fa-telegram"></i></a>
                </div>

                <!-- Menu utente -->
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="btn btn-outline-light rounded-circle d-flex align-items-center justify-content-center dropdown-toggle" 
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 45px; height: 45px;">
                                <i class="bi bi-person-circle"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">    
                                @if(Auth::user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard Admin
                                    </a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                        <i class="fas fa-user me-2"></i> La Mia Area
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.prenotazioni') }}">
                                        <i class="fas fa-calendar me-2"></i> Le Mie Prenotazioni
                                    </a></li>
                                @endif
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
                        </li>
                    @else
                        <li class="nav-item ms-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-light rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="bi bi-person-circle"></i>
                            </a>
                        </li>
                    @endauth
                </ul>

                <div class="language-switcher">
        <a href="{{ route('lang.switch', 'it') }}" class="me-2">🇮🇹</a>
        <a href="{{ route('lang.switch', 'en') }}">🇺🇸</a>
    </div>
<!-- Mostra la lingua attiva per debug -->
<p>Lingua corrente: {{ app()->getLocale() }}</p>

            </div>
    </div>
</nav>





    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    @if(!request()->routeIs('login', 'register'))
        <!-- Header (non mostrare su login/register) -->
        <header class="bg-primary text-white text-center py-2">
            <h1>Villetta Artale Marina</h1>
            <p class="lead">La tua casa vacanza sul Mare e alle pendici dell'Etna</p>
        </header>
    @endif

    <!-- Contenuto dinamico -->
    <main class="@if(request()->routeIs('login', 'register')) login-container @else container my-5 @endif">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        &copy; {{ date('Y') }} Villetta Artale Marina   (<a href="https://github.com/virgillito-tech" target="_blank" class="text-white">by virgillito-tech</a>)
    </footer>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

    <script>
        const lightbox = GLightbox({
            touchNavigation: true,
            loop: true,
            zoomable: true,
            draggable: true,
        });
    </script>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/it.global.min.js'></script>
    
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/it.js"></script>

    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>


    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    @if(!request()->routeIs('login', 'register'))
        <!-- Script Flatpickr solo per pagine non-login -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingForm = document.getElementById('bookingForm');
            
            if (bookingForm) {
                bookingForm.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            }

            const fp = flatpickr("#daterange", {
                mode: "range",
                dateFormat: "Y-m-d",
                minDate: "today",
                locale: "it",
                static: true,
                clickOpens: true,
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates && selectedDates.length === 2) {
                        if (selectedDates[0] instanceof Date && selectedDates[1] instanceof Date) {
                            try {
                                const checkin = instance.formatDate(selectedDates[0], "Y-m-d");
                                const checkout = instance.formatDate(selectedDates[1], "Y-m-d");
                                
                                document.getElementById('data_inizio').value = checkin;
                                document.getElementById('data_fine').value = checkout;
                            } catch (error) {
                                console.error('Errore nel formatDate:', error);
                            }
                        }
                    } else {
                        document.getElementById('data_inizio').value = '';
                        document.getElementById('data_fine').value = '';
                    }
                }
            });

            if (bookingForm) {
                bookingForm.addEventListener('submit', function(event) {
                    const checkin = document.getElementById('data_inizio').value;
                    const checkout = document.getElementById('data_fine').value;
                    const rooms = document.getElementById('rooms').value;
                    const guests = document.getElementById('guests').value;

                    if (!checkin || !checkout) {
                        alert('Devi selezionare sia check-in che check-out.');
                        event.preventDefault();
                        return;
                    }

                    if (!rooms || !guests) {
                        alert('Seleziona sia il numero di stanze che il numero di persone.');
                        event.preventDefault();
                        return;
                    }
                });
            }
        });
        </script>
    @endif

<script>
    document.addEventListener("scroll", function() {
        const navbar = document.querySelector(".navbar");
        if (window.scrollY > 50) {
            navbar.classList.add("shrink");
        } else {
            navbar.classList.remove("shrink");
        }
    });
</script>


    @stack('scripts')
</body>
</html>
