@extends($locale . '.layouts.app')

@php use Carbon\Carbon; @endphp

@section('title', 'Contatti - Villetta Artale Marina')

@section('content')
@if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="container-sfondo my-4">
    <h2>Contattaci</h2>

    <p> ✉️ <strong>E-mail:</strong>  <a href="mailto:villettartalemarina@gmail.com?subject=Richiesta%20informazioni&body=Buongiorno,%20vorrei%informazioni...">
    villettartalemarina@gmail.com</a> </p>
    <p>📞 <strong>Telefono:</strong> <a href="tel:3454218671">3454218671</a> oppure <a href="tel:3409727744">3409727744</a></p>

    <div class="mt-3">
        <a href="https://wa.me/393454218671" target="_blank" class="btn btn-success me-2">
            <i class="fab fa-whatsapp"></i> WhatsApp
        </a>
        <a href="https://t.me/virgillito" target="_blank" class="btn btn-outline-primary me-2">
            <i class="bi bi-telegram"></i> Telegram
        </a>
        <a href="https://www.instagram.com/villetta_artale_marina" target="_blank" class="btn btn-danger me-2">
            <i class="fab fa-instagram"></i> Instagram
        </a>
    </div>




<div class="d-flex gap-4 flex-wrap">
  <div style="flex: 1; min-width: 300px;">
    <h4 class="mt-4 mb-3">Dove ci trovi: </h4>
    <h6 class="mt-4 mb-3">📍Via Parallela alla Spiaggia 26, Mascali (CT), 95016</h6>
    <div class="ratio ratio-16x9" style="width: 100%;">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3154.7116771177043!2d15.20262147648995!3d37.74990781359094!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1314051d43ed4ecf%3A0xaace08279ad23c13!2sVilletta%20Artale%20Marina!5e0!3m2!1sit!2sit!4v1754602572424!5m2!1sit!2sit"
        style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>

  <div style="flex: 1; min-width: 300px; display: flex; flex-direction: column; justify-content: flex-start;">
    <h4 class="mt-4 mb-3">Meteo</h4>
    @if($weather)
      <div class="meteo-container p-3 rounded shadow-sm" style="background: linear-gradient(135deg,#87CEFA,#1E90FF); color: #fff; height: 100%;">
        <div class="d-flex align-items-center">
          <img src="https://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@2x.png" alt="Icona" style="width:64px;height:64px;">
          <div class="ms-3">
            <h5 class="mb-0">{{ $weather['name'] }}</h5>
            <small style="text-transform: capitalize;">{{ $weather['weather'][0]['description'] }}</small>
          </div>
        </div>

        <div class="mt-3">
          <p class="mb-1"><strong>{{ round($weather['main']['temp']) }}°C</strong> (feels {{ round($weather['main']['feels_like']) }}°C)</p>
          <p class="mb-1">Umidità: {{ $weather['main']['humidity'] }}%</p>
          <p class="mb-0">Vento: {{ $weather['wind']['speed'] }} m/s</p>
        </div>
      </div>
      @if($forecast && isset($forecast['daily']))
    <div class="mt-4">
        <h6>Previsioni prossimi 4 giorni</h6>
        <div class="d-flex justify-content-between flex-wrap">
            @foreach(array_slice($forecast['daily'], 1, 4) as $day)
                <div class="text-center p-2" style="min-width: 70px;">
                    <strong>{{ \Carbon\Carbon::createFromTimestamp($day['dt'])->format('D d M') }}</strong><br>
                    <img src="https://openweathermap.org/img/wn/{{ $day['weather'][0]['icon'] }}@2x.png" alt="Icona" style="width:50px; height:50px;"><br>
                    <span>{{ round($day['temp']['day']) }}°C</span><br>
                    <small style="text-transform: capitalize;">{{ $day['weather'][0]['description'] }}</small>
                </div>
            @endforeach
        </div>
    </div>
@endif
    @else
      <div class="alert alert-secondary" role="alert">
        Impossibile recuperare il meteo in questo momento.
      </div>
    @endif
  </div>

</div>
@endsection

<script>
function initMap() {
    var location = { lat: 37.74990781359094, lng: 15.20262147648995 }; // coordinate della villetta

    var map = new google.maps.Map(document.getElementById("map"), {
        zoom: 16,
        center: location,
    });

    var marker = new google.maps.Marker({
        position: location,
        map: map,
        title: "Villetta Artale Marina",
        icon: "{{ asset('images/logo.png') }}", // logo del sito come marker
    });
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
