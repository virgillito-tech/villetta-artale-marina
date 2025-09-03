<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prenotazione; // supponendo tu abbia un modello Prenotazione
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
        $user = Auth::user();
        return view('user.dashboard', compact('user'));
    }

    /**
     * Lista prenotazioni dell'utente
     */
    public function prenotazioni()
    {
        $user = Auth::user();
        $prenotazioni = Prenotazione::where('user_id', $user->id)->orderBy('check_in', 'desc')->get();

        return view('user.prenotazioni', compact('prenotazioni'));
    }

    /**
     * Modifica profilo
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('user.edit_profile', compact('user'));
    }

    /**
     * Aggiorna profilo
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:25',
            'indirizzo' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        $user->nome = $request->nome;
        $user->cognome = $request->cognome;
        $user->telefono = $request->telefono;
        $user->indirizzo = $request->indirizzo;
        $user->username = $request->username;
        $user->email = $request->email;

        if($request->filled('password')){
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('user.dashboard')->with('success', 'Profilo aggiornato con successo!');
    }
}
