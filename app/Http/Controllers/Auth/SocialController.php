<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        // Usa stateless per evitare InvalidStateException
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Dati raw restituiti da Google
        $raw = $googleUser->user;

        // Estrarre cognome
        $cognome = $raw['family_name'] ?? '';

        // Estrarre genere se presente (Google spesso non lo fornisce)
        $sesso = $raw['gender'] ?? null;

        // Estrarre data di nascita se presente (Google non sempre la fornisce)
        $dataNascita = $raw['birthday'] ?? null;

        // Estrarre telefono se disponibile (molto raro da Google)
        $telefono = $raw['phone_number'] ?? null;

        // Crea o trova utente
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'username'    => $googleUser->getName(),
                'nome'        => $googleUser->getName(),
                'cognome'     => $cognome,
                'sesso'       => $sesso,
                'data_nascita'=> $dataNascita,
                'telefono'    => $telefono,
                'password'    => bcrypt(str()->random(16)), // password random per social login
            ]
        );

        Auth::login($user);

        return redirect()->route('home');
    }

    // public function redirectToApple()
    // {
    //     return Socialite::driver('apple')->redirect();
    // }

    // public function handleAppleCallback()
    // {
    //     $appleUser = Socialite::driver('apple')->user();

    //     $user = User::firstOrCreate(
    //         ['email' => $appleUser->getEmail()],
    //         [
    //             'username' => $appleUser->getName() ?? 'utenteApple_' . uniqid(),
    //             'password' => bcrypt(str()->random(16)),
    //         ]
    //     );

    //     Auth::login($user);

    //     return redirect()->route('home');
    // }
}
