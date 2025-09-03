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
        $googleUser = Socialite::driver('google')->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'username' => $googleUser->getName(),
                'password' => bcrypt(str()->random(16)),
            ]
        );

        Auth::login($user);

        return redirect()->route('home');
    }

    public function redirectToApple()
    {
        return Socialite::driver('apple')->redirect();
    }

    public function handleAppleCallback()
    {
        $appleUser = Socialite::driver('apple')->user();

        $user = User::firstOrCreate(
            ['email' => $appleUser->getEmail()],
            [
                'username' => $appleUser->getName() ?? 'utenteApple_' . uniqid(),
                'password' => bcrypt(str()->random(16)),
            ]
        );

        Auth::login($user);

        return redirect()->route('home');
    }
}
