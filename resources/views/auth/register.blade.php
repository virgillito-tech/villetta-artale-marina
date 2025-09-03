@extends('layouts.app')

@section('content')
<!-- Select2 CSS (puoi spostarlo in <head> se preferisci) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<!-- intl-tel-input CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css"/>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-5" style="max-width: 600px; width: 100%; border-radius: 15px; position: relative; overflow: hidden;">
        
        <!-- Sfondo trasparente con logo -->
        <div style="position:absolute; top:0; left:0; width:100%; height:100%; background:url('{{ asset('images/logo.jpg') }}') center center no-repeat; background-size:300px; opacity:0.05; z-index:0;"></div>
        
        <div class="text-center mb-4" style="z-index:1; position:relative;">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo del Sito" class="mb-3" style="max-width: 150px;">
            <h2 class="fw-bold text-dark">Registrazione</h2>
        </div>

        <form method="POST" action="{{ route('register') }}" style="z-index:1; position:relative;">
            @csrf

            <!-- Username -->
            <div class="mb-4">
                <label for="username" class="form-label">Username</label>
                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autofocus>
                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Codice Fiscale -->
            <div class="mb-4">
                <label for="codice_fiscale" class="form-label">Codice Fiscale</label>
                <input id="codice_fiscale" type="text" maxlength="16" class="form-control @error('codice_fiscale') is-invalid @enderror" name="codice_fiscale" value="{{ old('codice_fiscale') }}" required>
                @error('codice_fiscale') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Nome -->
            <div class="mb-4">
                <label for="nome" class="form-label">Nome</label>
                <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" required>
                @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Cognome -->
            <div class="mb-4">
                <label for="cognome" class="form-label">Cognome</label>
                <input id="cognome" type="text" class="form-control @error('cognome') is-invalid @enderror" name="cognome" value="{{ old('cognome') }}" required>
                @error('cognome') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Nazionalità -->
            <div class="mb-4">
                <label for="nazionalita" class="form-label">Nazionalità</label>
                <select name="nazionalita" id="nazionalita" class="form-select select2 @error('nazionalita') is-invalid @enderror" required>
                    <option value="">Seleziona nazionalità...</option>
                    @foreach($nazioni as $nazione)
                        <option value="{{ $nazione }}" {{ old('nazionalita') == $nazione ? 'selected' : '' }}>
                            {{ $nazione }}
                        </option>
                    @endforeach
                </select>
                @error('nazionalita') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Sesso -->
            <div class="mb-4">
                <label for="sesso" class="form-label">Sesso</label>
                <select id="sesso" name="sesso" class="form-select @error('sesso') is-invalid @enderror" required>
                    <option value="" disabled {{ old('sesso') ? '' : 'selected' }}>Seleziona</option>
                    <option value="M" {{ old('sesso') == 'M' ? 'selected' : '' }}>Maschile</option>
                    <option value="F" {{ old('sesso') == 'F' ? 'selected' : '' }}>Femminile</option>
                    <option value="Altro" {{ old('sesso') == 'Altro' ? 'selected' : '' }}>Altro</option>
                </select>
                @error('sesso') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Data e Luogo di Nascita -->
            <div class="row mb-4">
                <div class="col">
                    <label for="data_nascita" class="form-label">Data di Nascita</label>
                    <input id="data_nascita" type="date" class="form-control @error('data_nascita') is-invalid @enderror" name="data_nascita" value="{{ old('data_nascita') }}" required>
                    @error('data_nascita') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col">
                    <label for="luogo_nascita" class="form-label">Luogo di Nascita</label>
                    <input id="luogo_nascita" type="text" class="form-control @error('luogo_nascita') is-invalid @enderror" name="luogo_nascita" value="{{ old('luogo_nascita') }}" required>
                    @error('luogo_nascita') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Indirizzo di Residenza (con Google Autocomplete) -->
            <div class="mb-4">
                <label for="indirizzo_residenza" class="form-label">Indirizzo di Residenza</label>
                <input id="indirizzo_residenza" type="text" class="form-control @error('indirizzo_residenza') is-invalid @enderror" name="indirizzo_residenza" value="{{ old('indirizzo_residenza') }}" placeholder="Inserisci indirizzo">
                @error('indirizzo_residenza') <div class="invalid-feedback">{{ $message }}</div> @enderror

                <!-- campi hidden per lat/lng -->
                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
            </div>

            <!-- Telefono -->
            <div class="mb-4">
                <label for="telefono" class="form-label">Telefono</label>
                <input id="telefono" type="tel" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono') }}" required>
                @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="form-label">Indirizzo Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Conferma Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Conferma Password</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">Registrati</button>

            <div class="d-grid gap-2 mt-3">
    <a href="{{ url('auth/google') }}" class="btn btn-danger">
        <i class="fab fa-google me-2"></i> Accedi con Google
    </a>
    <a href="{{ url('auth/apple') }}" class="btn btn-dark">
        <i class="fab fa-apple me-2"></i> Accedi con Apple
    </a>
</div>
        </form>
    </div>
</div>

<!-- JS dependencies: jQuery, Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- intl-tel-input JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>


<script>
    // Init Select2 - attendi che jQuery e Select2 siano caricati
    $(document).ready(function() {
        $('#nazionalita').select2({
            theme: 'bootstrap-5',
            placeholder: "Seleziona una nazionalità",
            allowClear: true,
            width: '100%'
        });
    });

    // Google Places: definisci la callback che verrà chiamata quando lo script Maps è pronto
    function initAutocomplete() {
        var input = document.getElementById('indirizzo_residenza');
        if (!input) return;

        var autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['address'] // o ['geocode'] a seconda delle necessità
            // componentRestrictions: { country: "it" } // se vuoi limitare a Italia
        });

        // richiedi questi campi per maggiore stabilità
        autocomplete.setFields(['formatted_address','geometry']);

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            if (!place.geometry) return; // niente geometry => nulla da salvare

            // salva formattato e coordinate
            document.getElementById('indirizzo_residenza').value = place.formatted_address || document.getElementById('indirizzo_residenza').value;

            if (place.geometry && place.geometry.location) {
                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();
            }
        });
    }

document.addEventListener("DOMContentLoaded", function () {
    var input = document.querySelector("#telefono");
    var iti = window.intlTelInput(input, {
        initialCountry: "it", // default Italia
        preferredCountries: ["it", "us", "fr", "de"], // metti quelle che vuoi in cima
        separateDialCode: true, // mostra prefisso fuori dal numero
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
    });

    // Aggiorna il valore del campo con prefisso completo prima dell'invio
    var form = input.closest("form");
    form.addEventListener("submit", function () {
        if (iti.isValidNumber()) {
            input.value = iti.getNumber(); // es: +393491234567
        }
    });
});


</script>

<!-- Google Maps Places: sostituisci YOUR_GOOGLE_API_KEY con la tua chiave reale -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBClK3qPwdnVWNF5cbnMzngxk6_5DYM3ss&libraries=places&callback=initAutocomplete" async defer></script>

@endsection
