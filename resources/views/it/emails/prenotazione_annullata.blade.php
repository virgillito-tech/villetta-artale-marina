<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazione Cancellata</title>
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
        <h1 style="color: #2c3e50;">🏖️ Prenotazione Cancellata</h1>
        
            <h3>Ciao {{ $prenotazione->nome }},</h3>
            
            <p>Ti confermiamo che la tua prenotazione<strong>{{ $prenotazione->codice_prenotazione }}</strong> presso Villetta Artale Marina è stata cabcellata con successo.</p>
            
            <p>Come richiesto abbiamo emesso il rimborso di <strong>€{{ number_format($prenotazione->prezzo_totale, 2, ',', '.') }}</strong> al metodo di pagamento che hai usato.</p>
            
            <p>I tempi di accredito possono variare da 5 a 10 giorni lavorativi, a seconda della banca o dell'emittente della carta.</p>
            
            <hr>
            <h4>Dettagli prneotazione cancellata:</h4>
            <ul>
                <li><strong>Codice:</strong> {{ $prenotazione->codice_prenotazione }}</li>
                <li><strong>Date:</strong> Dal {{ \Carbon\Carbon::parse($prenotazione->data_inizio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($prenotazione->data_fine)->format('d/m/Y') }}</li>
                <li><strong>Totale rimborso:</strong> €{{ number_format($prenotazione->prezzo_totale, 2, ',', '.') }}</li>
            </ul>
            <hr>
            
            <p>Ci dispiace vederti annullare. Speriamo di ospitarti di nuovo in un'altra occasione..</p>
            
            <p>Grazie,<br>
            Staff Villetta Artale Marina</p>
         <p style="margin-top: 30px;">
            <small>Per altre informazioni: villettartalemarina@gmail.com</small>
        </p>

        <div class="email-footer">
            Villetta Artale Marina &copy; {{ date('Y') }}<br>
            <a href="https://github.com/virgillito-tech" target="_blank" class="text-white">by virgillito-tech</a><br>
            Se non hai richiesto questa registrazione, ignora questa email.
        </div>
    </div>
    </div>
</body>
</html>
