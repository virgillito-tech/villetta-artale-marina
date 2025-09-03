@extends('layouts.app')

@section('title', 'Servizi')

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
<div class="container my-5">
    <h1 class="mb-4 text-center">Servizi Offerti</h1>

    <div class="row row-cols-1 row-cols-md-2 g-4">

        <div class="col d-flex align-items-start">
            <i class="bi bi-car-front-fill me-3 fs-2 text-primary"></i>
            <div>
                <h5>Parcheggio gratuito</h5>
                <p>Comodo parcheggio interno incluso nel soggiorno.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-ev-front-fill me-3 fs-2 text-success"></i>
            <div>
                <h5>Ricarica auto elettriche</h5>
                <p>Colonnina di ricarica privata disponibile su richiesta.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-wifi me-3 fs-2 text-info"></i>
            <div>
                <h5>Wi-Fi veloce</h5>
                <p>Connessione stabile e potente in tutta la struttura.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-tv-fill me-3 fs-2 text-dark"></i>
            <div>
                <h5>TV Smart</h5>
                <p>TV con accesso a Netflix, YouTube e altri servizi.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-snow me-3 fs-2 text-primary"></i>
            <div>
                <h5>Stanze condizionate</h5>
                <p>Ogni stanza è dotata di aria condizionata.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-cup-hot-fill me-3 fs-2 text-warning"></i>
            <div>
                <h5>Bollitore</h5>
                <p>Disponibile bollitore per tè, tisane e caffè.</p>
            </div>
        </div>

        
        <div class="col d-flex align-items-start">
            <i class="bi bi-cup-hot-fill me-3 fs-2 text-danger"></i>
            <div>
                <h5>Macchinetta del caffè</h5>
                <p>Macchina da caffè a capsule a disposizione degli ospiti.</p>
            </div>
        </div>

        
        <div class="col d-flex align-items-start">
            <i class="bi bi-droplet-half me-3 fs-2 text-primary"></i>
            <div>
                <h5>Lavastoviglie</h5>
                <p>Comoda lavastoviglie per soggiorni lunghi o con la famiglia.</p>
            </div>
        </div>

        
        <div class="col d-flex align-items-start">
           <i class="bi bi-fire me-3 fs-2 text-secondary"></i>
            <div>
                <h5>Forno</h5>
                <p>Forno elettrico moderno a disposizione degli ospiti.</p>
            </div>
        </div>

        {{-- NUOVO: Camere da letto --}}
        <div class="col d-flex align-items-start">
            <i class="bi bi-house-door-fill me-3 fs-2 text-secondary"></i>
            <div>
                <h5>2 Camere da letto</h5>
                <p>Due ampie camere con letti matrimoniali e armadi capienti.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-badge-wc-fill me-3 fs-2 text-secondary"></i>
            <div>
                <h5>2 Bagni</h5>
                <p>Due bagni disponibili nei due piani.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-tree-fill me-3 fs-2 text-success"></i>
            <div>
                <h5>Ampio giardino con zona relax</h5>
                <p>Divani, sedie e tavolinetti all'aperto per godersi il verde.</p>
            </div>
        </div>


    </div>
</div>
@endsection