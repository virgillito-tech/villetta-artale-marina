<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
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
<body>
    <div class="email-wrapper">
       <div class="email-header">
    <img src="{{ $message->embed(public_path('images/logo.jpg')) }}" alt="Villetta Artale Marina" 
         style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
</div>

        <div class="email-body">
            <h1 style="color: #2c3e50;">Villetta Artale Marina</h1>
            <h2>Verify your email address</h2>
            <p>Hi {{ $user->nome }}, thanks for sign in to Villetta Artale Marina!<br>
           Click the button below to confirm your email address and complete your registration..</p>

            <a href="{{ $verificationUrl }}" class="btn-verify">Verify Email</a>
        </div>

        <div class="email-footer">
            Villetta Artale Marina &copy; {{ date('Y') }}<br>
            <a href="https://github.com/virgillito-tech" target="_blank" class="text-white">by virgillito-tech</a><br>
            If you did not request this registration, please ignore this email.
        </div>
    </div>
</body>
</html>
