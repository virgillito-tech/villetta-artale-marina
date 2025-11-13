<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazione Confermata</title>
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
            padding: 30px 20px;
            text-align: center;
        }
        .email-body h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .email-body p {
            font-size: 16px;
            margin-bottom: 25px;
        }
        .btn-verify {
            display: inline-block;
            padding: 12px 25px;
            font-size: 16px;
            color: #fff;
            background: #0d6efd;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            padding: 15px;
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
     <div class="email-wrapper">
       <div class="email-header">
    <img src="{{ $message->embed(public_path('images/logo.jpg')) }}" alt="Villetta Artale Marina" 
         style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
</div>
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2c3e50;">🏖️ Prenotazione Confermata!</h1>
        
        <p>Gentile <strong>{{ $prenotazione->nome }}</strong>,</p>
        
        <p>La sua prenotazione è stata <strong>confermata automaticamente</strong>!</p>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3>Dettagli Prenotazione:</h3>
            <p><strong>Codice:</strong> VIL-{{ str_pad($prenotazione->id, 4, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($prenotazione->data_inizio)->format('d/m/Y') }}</p>
            <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($prenotazione->data_fine)->format('d/m/Y') }}</p>
            <p><strong>Persone:</strong> {{ $prenotazione->numero_persone }}</p>
            <p><strong>Stanze:</strong> {{ $prenotazione->numero_stanze }}</p>
            <p><strong>Prezzo Totale:</strong> €{{ number_format($prenotazione->prezzo_totale, 2, ',', '.') }}</p>
        </div>
        
        <p>Ci vediamo presto alla Villetta Artale Marina! 🌊</p>
        
        <p style="margin-top: 30px;">
            <small>Per qualsiasi informazione: villettartalemarina@gmail.com</small>
        </p>
    </div>
</body>
</html>
