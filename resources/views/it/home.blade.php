@extends($locale . '.layouts.app')

@section('title', 'Home - Villetta Artale Marina')

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
<div class="hero bg-light py-5 text-center">
    <div class="container">
        <h1 class="display-4 fw-bold" data-aos="fade-up">Benvenuti in Villetta Artale Marina</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100">
            Comfort, mare e relax nel cuore della Sicilia.
        </p>
    </div>
</div>

<!-- Anteprima Struttura -->
<div class="container my-5">
    <div class="row g-4 align-items-center">
        <div class="col-md-6" data-aos="fade-right">
            <div class="image-box shadow">
                <img src="{{ asset('images/galleria/esterno.jpeg') }}" alt="Esterno Villetta">
            </div>
        </div>
        <div class="col-md-6" data-aos="fade-left">
            <h2 class="fw-bold">La Nostra Struttura</h2>
            <p class="section-text">
                Situata in un complesso privato, la Villetta Artale Marina è composta da due piani: zona giorno e zona notte,
                entrambi con bagno completo di servizi.
            </p>
        </div>
    </div>
</div>

<!-- Piano Notte -->
<div class="container my-5">
    <div class="row g-4 align-items-center flex-md-row-reverse">
        <div class="col-md-6" data-aos="fade-left">
            <div class="image-box shadow">
                <img src="{{ asset('images/galleria/camera1.jpg') }}" alt="Piano Notte">
            </div>
        </div>
        <div class="col-md-6" data-aos="fade-right">
            <h2 class="fw-bold">Piano Notte</h2>
            <p class="section-text">
                Al piano superiore sono presenti due camere da letto con ampi letti matrimoniali, TV Smart e condizionatori 
                per il riscaldamento/raffreddamento. Inoltre, ampie finestre a tetto con tende oscuranti garantiscono luce e privacy.
            </p>
        </div>
    </div>
</div>

<!-- Piano Giorno -->
<div class="container my-5">
    <div class="row g-4 align-items-center">
        <div class="col-md-6" data-aos="fade-right">
            <div class="image-box shadow">
                <img src="{{ asset('images/galleria/cucina.jpg') }}" alt="Piano Giorno">
            </div>
        </div>
        <div class="col-md-6" data-aos="fade-left">
            <h2 class="fw-bold">Piano Giorno</h2>
            <p class="section-text">
                Al piano inferiore si trova la cucina climatizzata, con angolo cottura, lavastoviglie, forno, bollitore, 
                macchinetta del caffè e tostapane, oltre ad un ampio tavolo per la massima comodità.  
                Il soggiorno ospita un comodo divano letto e una Smart TV per momenti di relax.
            </p>
        </div>
    </div>
</div>

<!-- Giardino -->
<div class="container my-5">
    <div class="row g-4 align-items-center flex-md-row-reverse">
        <div class="col-md-6" data-aos="fade-left">
            <div class="image-box shadow">
                <img src="{{ asset('images/galleria/giardino.jpg') }}" alt="Giardino">
            </div>
        </div>
        <div class="col-md-6" data-aos="fade-right">
            <h2 class="fw-bold">Giardino</h2>
            <p class="section-text">
                L’ampio giardino offre poltrone, sedie sdraio e tavolini per godersi il sole siciliano.  
                Perfetto per cene romantiche al chiaro di luna.  
                A disposizione anche lavatrice, pilotto e parcheggio gratuito con possibilità di ricarica auto elettriche (a pagamento).
            </p>
        </div>
    </div>
</div>

<!-- Invito alla Prenotazione -->
<div class="container text-center my-5" data-aos="zoom-in">
    <h2 class="fw-bold">Vivi la tua Vacanza da Sogno</h2>
    <p>Scopri comfort, relax e il mare cristallino della Sicilia.</p>
    <a href="{{ route('contatti') }}" class="btn btn-success btn-lg shadow">Contattaci Ora</a>
</div>
@endsection