<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prenotazione; 
use App\Models\User;

class UserController extends Controller
{
    // Middleware per proteggere il controller
    public function __construct()
    {
        $this->middleware(['auth', 'verified']); // solo utenti loggati e verificati
    }

    /**
     * Dashboard utente
     */
    public function dashboard()
    {
        $locale = app()->getLocale();
        $user = Auth::user();
        return view($locale . '.user.dashboard', compact('user', 'locale'));
    }

    /**
     * Lista prenotazioni dell'utente
     */
    public function prenotazioni()
    {
        $locale = app()->getLocale();
        $user = Auth::user();
        $prenotazioni = Prenotazione::where('user_id', $user->id)->orderBy('check_in', 'desc')->get();

        return view($locale . '.user.prenotazioni', compact('prenotazioni', 'locale'));
    }


}
