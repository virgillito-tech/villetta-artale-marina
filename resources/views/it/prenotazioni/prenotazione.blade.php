@extends($locale . '.layouts.app')

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
<div class="container mt-5">
    <h2 class="mb-4 text-center">Calendario Prenotazioni</h2>

   @include($locale . '.partials.calendario', ['prenotazioni' => $prenotazioni])

</div>
@endsection