@extends($locale . '.layouts.app')
@section('title', 'La Mia Area - Villetta Artale Marina')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex align-items-center mb-4">
            <i class="fas fa-user fa-2x text-primary me-3"></i>
            <div>
                <h2 class="mb-0">Welcome, {{ Auth::user()->nome }}!</h2>
                <p class="text-muted mb-0">Your Personal Area</p>
            </div>
        </div>


        <div class="container my-5">
    <h2 class="mb-4">My Profile</h2>

    <div class="card shadow-sm border-0 rounded-4 p-4" style="background: #f8f9fa;">
        <div class="d-flex align-items-center mb-4">
            <div class="me-4">
                <i class="fas fa-user-circle fa-5x text-primary"></i>
            </div>
            <div>
                <h3 class="mb-1">{{ Auth::user()->nome }}</h3>
                <p class="text-muted mb-0">Welcome to your Personal Area</p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 mb-2">
                <p class="mb-1"><i class="fas fa-envelope me-2 text-primary"></i> {{ Auth::user()->email }}</p>
            </div>
            <div class="col-md-6 mb-2">
                <p class="mb-1"><i class="fas fa-phone me-2 text-success"></i> {{ Auth::user()->telefono ?? 'Not inserted' }}</p>
            </div>
            @if(Auth::user()->isAdmin())
            <div class="col-md-6 mb-2">
                <p class="mb-1"><i class="fas fa-id-badge me-2 text-warning"></i> Ruolo: 
                    
                        Admin
                    
                </p>
            </div>
            @endif
        </div>

        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-lg mt-3">
            <i class="fas fa-edit me-2"></i> Edit Profile
        </a>
<a href="{{ route('profile.password.edit') }}" class="btn btn-primary btn-lg mt-3">Edit Password</a>



    </div>
</div>

        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-plus fa-3x text-success mb-3"></i>
                        <h5>New Reservation</h5>
                        <p>Book your stay at the Villetta</p>
                        <button class="btn btn-success" onclick="document.getElementById('bookingDropdown').click()">
                           Book Now
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-list fa-3x text-info mb-3"></i>
                        <h5>My Reservations</h5>
                        <p>View your bookings</p>
                        <a href="{{ route('user.prenotazioni') }}" class="btn btn-info">
                            See Reservations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
