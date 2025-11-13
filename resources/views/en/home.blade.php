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
        <h1 class="display-4 fw-bold" data-aos="fade-up">Welcome to Villetta Artale Marina</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100">
            Comfort, sea and relax in the hearth of Sicily.
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
            <h2 class="fw-bold">Our House</h2>
            <p class="section-text">
                Located in a private complex, Villetta Artale Marina comprises two floors: a living area and a sleeping area,
both with a full bathroom.
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
            <h2 class="fw-bold">Night Plan</h2>
            <p class="section-text">
               Upstairs are two bedrooms with large double beds, Smart TVs, and air conditioning
for heating and cooling. Large skylights with blackout curtains ensure plenty of light and privacy.
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
            <h2 class="fw-bold">Day Plan</h2>
            <p class="section-text">
                On the lower level is the air-conditioned kitchen, complete with a kitchenette, dishwasher, oven, kettle,
coffee maker, and toaster, as well as a large table for maximum convenience.
The living room features a comfortable sofa bed and a Smart TV for relaxing moments.
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
            <h2 class="fw-bold">Garden</h2>
            <p class="section-text">
                The large garden offers armchairs, deck chairs, and tables to enjoy the Sicilian sun.
Perfect for romantic moonlit dinners.
A washing machine, a pilot house, and free parking with electric car charging facilities (for a fee) are also available.
            </p>
        </div>
    </div>
</div>

<!-- Invito alla Prenotazione -->
<div class="container text-center my-5" data-aos="zoom-in">
    <h2 class="fw-bold">Live your Dream Vacation</h2>
    <p>Discover comfort, relaxation, and the crystal-clear sea of ​​Sicily..</p>
    <a href="{{ route('contatti') }}" class="btn btn-success btn-lg shadow">Contact Us Now</a>
</div>
@endsection