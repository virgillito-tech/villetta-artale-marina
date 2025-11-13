@extends($locale . '.layouts.app')

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
    <h1 class="mb-4 text-center">Services Offered</h1>

    <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="col d-flex align-items-start">
            <i class="bi bi-paypal me-3 fs-2 text-primary"></i>
            <div>
                <h5>PayPal</h5>
                <p>Payments with PayPal and Stripe</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-credit-card me-3 fs-2 text-primary"></i>
            <div>
                <h5>Free refund</h5>
                <p>Free refund within 30 days of booking</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-car-front-fill me-3 fs-2 text-primary"></i>
            <div>
                <h5>Parking Free</h5>
                <p>Convenient internal parking included in the stay.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-ev-front-fill me-3 fs-2 text-success"></i>
            <div>
                <h5>Electric car charging</h5>
                <p>Private charging station available upon request.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-wifi me-3 fs-2 text-info"></i>
            <div>
                <h5>Fast Wi-Fi</h5>
                <p>Stable and powerful connection throughout the property.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-tv-fill me-3 fs-2 text-dark"></i>
            <div>
                <h5>Smart TV</h5>
                <p>TV with access to Netflix, Disney+, Apple TV, YouTube and other services.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-snow me-3 fs-2 text-primary"></i>
            <div>
                <h5>Air-conditioned rooms</h5>
                <p>The main rooms are equipped with air conditioning.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-cup-hot-fill me-3 fs-2 text-warning"></i>
            <div>
                <h5>Kettle</h5>
                <p>Kettle for tea, herbal teas and coffee available.</p>
            </div>
        </div>

        
        <div class="col d-flex align-items-start">
            <i class="bi bi-cup-hot-fill me-3 fs-2 text-danger"></i>
            <div>
                <h5>Macchinetta del caffè</h5>
                <p>Capsule coffee machine available to guests.</p>
            </div>
        </div>

        
        <div class="col d-flex align-items-start">
            <i class="bi bi-droplet-half me-3 fs-2 text-primary"></i>
            <div>
                <h5>Lavastoviglie</h5>
                <p>Convenient dishwasher for long stays or with the family.</p>
            </div>
        </div>

        
        <div class="col d-flex align-items-start">
           <i class="bi bi-fire me-3 fs-2 text-secondary"></i>
            <div>
                <h5>Oven</h5>
                <p>Forno elettrico moderno a disposizione degli ospiti.</p>
            </div>
        </div>

        {{-- NUOVO: Camere da letto --}}
        <div class="col d-flex align-items-start">
            <i class="bi bi-house-door-fill me-3 fs-2 text-secondary"></i>
            <div>
                <h5>2 bedrooms</h5>
                <p>Two large bedrooms with double beds and spacious wardrobes.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-badge-wc-fill me-3 fs-2 text-secondary"></i>
            <div>
                <h5>2 Bathrooms</h5>
                <p>Two bathrooms available on both floors.</p>
            </div>
        </div>

        <div class="col d-flex align-items-start">
            <i class="bi bi-tree-fill me-3 fs-2 text-success"></i>
            <div>
                <h5>Large garden with relaxation area</h5>
                <p>Outdoor sofas, chairs and table to enjoy.</p>
            </div>
        </div>


    </div>
</div>
@endsection