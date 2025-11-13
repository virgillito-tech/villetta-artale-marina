@extends($locale . '.layouts.app')

@section('title', 'Pagamento annullato')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-warning text-center mt-4">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <h3>Pagamento annullato</h3>
                <p>La procedura di pagamento è stata annullata.<br>
                   Nessun importo è stato addebitato.</p>
                <a href="{{ url('/') }}" class="btn btn-primary mt-3">
                    Torna alla home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
