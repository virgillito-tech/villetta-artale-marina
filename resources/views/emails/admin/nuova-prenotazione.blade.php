<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuova Prenotazione Ricevuta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .email-header {
            background: #0d6efd; /* colore principale sito */
            padding: 20px;
            text-align: center;
        }
        .email-header img {
            max-width: 120px;
        }
        .email-body {
            /* Allineo a sinistra per una lettura più facile per l'admin */
            padding: 30px 40px; 
            text-align: left; 
            line-height: 1.6;
        }
        .email-body h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #0d6efd;
            text-align: center; /* Titolo centrato */
        }
        .email-body p {
            font-size: 16px;
            margin-bottom: 10px; /* Riduco per le liste */
        }
        .details-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .details-box h3 {
            margin-top: 0;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .payment-box {
            background: #e6f0ff; /* Un blu leggero per il pannello pagamento */
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        .btn-verify {
            display: inline-block;
            padding: 12px 25px;
            font-size: 16px;
            color: #fff !important; /* Forza il bianco sul link */
            background: #0d6efd;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            padding: 20px;
            background: #f5f5f5;
        }
    </style>
</head>
<body>
     <div class="email-wrapper">
        
        <div class="email-header">
    <img src="{{ $message->embed(public_path('images/logo.jpg')) }}" alt="Villetta Artale Marina" 
         style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
</div>

        <div class="email-body">
            <h1>🔔 Nuova Prenotazione!</h1>
            
            <p>Hai ricevuto una nuova prenotazione tramite il sito. Questi sono i dettagli:</p>

            <div class="details-box">
                <h3>Dettagli Cliente</h3>
                <p><strong>Nome:</strong> {{ $prenotazione->nome }}</p>
                <p><strong>Email:</strong> {{ $prenotazione->email }}</p>
                <p><strong>Telefono:</strong> {{ $prenotazione->telefono ?? 'N/D' }}</p>
            </div>
            
            <div class="details-box">
                <h3>Dettagli Soggiorno</h3>
                <p><strong>Codice:</strong> {{ $prenotazione->codice_prenotazione }}</p>
                <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($prenotazione->data_inizio)->format('d/m/Y') }}</p>
                <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($prenotazione->data_fine)->format('d/m/Y') }}</p>
                <p><strong>Persone:</strong> {{ $prenotazione->numero_persone }}</p>
                <p><strong>Stanze:</strong> {{ $prenotazione->numero_stanze }}</p>
                
                @if(!empty($prenotazione->note))
                    <h3 style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">Note Cliente:</h3>
                    <p style="white-space: pre-wrap; font-style: italic;">{{ $prenotazione->note }}</p>
                @endif
            </div>

            <div class="payment-box">
                <h3 style="margin-top: 0;">Dettagli Pagamento</h3>
                <p><strong>Importo Pagato:</strong> €{{ number_format($prenotazione->prezzo_totale, 2, ',', '.') }}</p>
                <p><strong>Metodo:</strong> {{ ucfirst($prenotazione->tipo_pagamento) }}</p>
            </div>

            <div class="button-container">
                <a href="{{ url('/admin/dashboard') }}" class="btn-verify">
                    Vai alla Dashboard
                </a>
            </div>

        </div>
    </div>

    <div class="email-footer">
        © {{ date('Y') }} Villetta Artale Marina.
    </div>
</body>
</html>