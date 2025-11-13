<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Cancelled</title>
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
        <h1 style="color: #2c3e50;">Villetta Artale Marina</h1>
        <h2 style="color: #2c3e50;">🏖️ Reservation Cancelled</h2>
        
            <h3>Hi {{ $prenotazione->nome }},</h3>
            
            <p>We confirm that your reservation <strong>{{ $prenotazione->codice_prenotazione }}</strong> for Villetta Artale Marina has been successfully cancelled.</p>
            
            <p>As per your request, we have initiated a full refund of <strong>€{{ number_format($prenotazione->prezzo_totale, 2, ',', '.') }}</strong> to the payment method you used.</p>
            
            <p>Crediting times can vary from 5 to 10 business days, depending on your bank or card issuer.</p>
            
            <hr>
            <h4>Cancelled booking details:</h4>
            <ul>
                <li><strong>Code:</strong> {{ $prenotazione->codice_prenotazione }}</li>
                <li><strong>Date:</strong> From {{ \Carbon\Carbon::parse($prenotazione->data_inizio)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($prenotazione->data_fine)->format('d/m/Y') }}</li>
                <li><strong>Amount refunded:</strong> €{{ number_format($prenotazione->prezzo_totale, 2, ',', '.') }}</li>
            </ul>
            <hr>
            
            <p>We're sorry to see you cancel. We hope to host you again another time.</p>
            
            <p>Thanks,<br>
            Staff Villetta Artale Marina</p>
         <p style="margin-top: 30px;">
            <small>For more information: villettartalemarina@gmail.com</small>
        </p>

        <div class="email-footer">
            Villetta Artale Marina &copy; {{ date('Y') }}<br>
            <a href="https://github.com/virgillito-tech" target="_blank" class="text-white">by virgillito-tech</a><br>
            If you did not request this registration, please ignore this email.
        </div>
    </div>
    </div>
</body>
</html>
