<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazione Annullata</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #333; margin: 0; padding: 0; }
        .email-wrapper { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .email-header { background: #dc3545; /* Rosso per annullamento */ padding: 20px; text-align: center; }
        .email-header img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; }
        .email-body { padding: 30px 40px; text-align: left; line-height: 1.6; }
        .email-body h1 { font-size: 24px; margin-bottom: 20px; color: #dc3545; text-align: center; }
        .email-body p { font-size: 16px; margin-bottom: 10px; }
        .details-box { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .details-box h3 { margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .cancellation-box { background: #fff8f8; border: 1px solid #dc3545; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .button-container { text-align: center; margin-top: 30px; }
        .btn-verify { display: inline-block; padding: 12px 25px; font-size: 16px; color: #fff !important; background: #0d6efd; border-radius: 8px; text-decoration: none; font-weight: bold; }
        .email-footer { text-align: center; font-size: 12px; color: #888; padding: 20px; background: #f5f5f5; }
    </style>
</head>
<body>
     <div class="email-wrapper">
        
        <div class="email-header">
            <img src="{{ $message->embed(public_path('images/logo.jpg')) }}" alt="Villetta Artale Marina">
        </div>

        <div class="email-body">
            <h1>❌ Prenotazione Annullata</h1>
            
            <p>Una prenotazione è stata annullata. Questi sono i dettagli:</p>

            <div class="cancellation-box">
                <h3 style="margin-top: 0;">Dettagli Annullamento</h3>
                <p><strong>Annullata da:</strong> {{ $annullataDa }}</p>
                <p><strong>Rimborso Emesso:</strong> {{ $rimborso }}</p>
            </div>

            <div class="details-box">
                <h3>Dettagli Soggiorno</h3>
                <p><strong>Codice:</strong> {{ $prenotazione->codice_prenotazione }}</p>
                <p><strong>Cliente:</strong> {{ $prenotazione->nome }} ({{ $prenotazione->email }})</p>
                <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($prenotazione->data_inizio)->format('d/m/Y') }}</p>
                <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($prenotazione->data_fine)->format('d/m/Y') }}</p>
                <p><strong>Importo Rimborsato:</strong> €{{ number_format($prenotazione->prezzo_totale, 2, ',', '.') }}</p>
                <p><strong>Metodo di Pagamento:</strong> {{ ucfirst($prenotazione->tipo_pagamento) }}</p>
            </div>

            <div class="button-container">
                <a href="{{ route('admin.prenotazioni.index') }}" class="btn-verify">
                    Vedi Prenotazioni
                </a>
            </div>

        </div>
    </div>

    <div class="email-footer">
        © {{ date('Y') }} Villetta Artale Marina.
    </div>
</body>
</html>