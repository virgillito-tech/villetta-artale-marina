<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // Mostra il profilo
    public function show()
    {
        return view('user.dashboard');
    }

    // Mostra il form di modifica
    public function edit(Request $request)
    {
        $user = $request->user();
        return view('user.profile.edit', compact('user'));
    }

   public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:20',
        ]);

        // Assegna i dati validati
        $user->nome = $request->nome;
        $user->cognome = $request->cognome;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->telefono = $request->telefono;

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profilo aggiornato con successo.');
    }

    // Aggiorna la password
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        // Controlla la password attuale
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La password attuale non è corretta.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Password aggiornata con successo.');
    }
}
