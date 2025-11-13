@extends($locale . '.layouts.app')
@section('title', 'Galleria - Villetta Artale Marina')

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
@php
    $images = array_merge(
        glob(public_path('images/galleria/*.jpg')),
        glob(public_path('images/galleria/*.jpeg')),
        glob(public_path('images/galleria/*.png')),
        glob(public_path('images/galleria/*.webp'))
    );

    $badges = [
    ['🛀','BathRoom'],
    ['🛏️','BedRoom'],
    ['🛏️','BedRoom'],
    ['🪑','Kitchen'],
    ['🌿','Garden'],
    ['🪑','Living Room'],
    ['🌅','View'],
    ['🌊','Beach']
];
@endphp



<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        AOS.init();
    });
</script>

<div class="container py-5">
    <h1 class="text-center mb-5">Our Gallery</h1>
    <div class="row g-4">
@foreach ($images as $index => $path)
    @php
        $relativePath = str_replace(public_path(), '', $path);
        [$icon, $label] = $badges[$index % count($badges)];
    @endphp
    <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
        <div class="photo-card">
            <span class="badge-overlay">{{ $icon }} {{ $label }}</span>
            <a href="{{ asset($relativePath) }}" class="glightbox" data-gallery="galleria">
                <img src="{{ asset($relativePath) }}" alt="Foto" class="img-fluid">
            </a>
        </div>
    </div>
@endforeach
    </div>
</div>
@endsection