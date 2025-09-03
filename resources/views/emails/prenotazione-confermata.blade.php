<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prenotazione Confermata</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
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
        </div>
        
        <p>Ci vediamo presto alla Villetta Artale Marina! 🌊</p>
        
        <p style="margin-top: 30px;">
            <small>Per qualsiasi informazione: villettartalemarina@gmail.com</small>
        </p>
    </div>
</body>
</html>
