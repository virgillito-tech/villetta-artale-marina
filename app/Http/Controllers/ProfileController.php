<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('verified');
    // }

    public function dashboard()
    {
        $locale = app()->getLocale();
        $user = Auth::user();
        return view($locale . '.user.dashboard', compact('user', 'locale'));
    }

    public function edit()
    {
        $locale = app()->getLocale();
        $user = Auth::user();
        return view($locale . '.user.profile.edit', compact('user', 'locale'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:20',
            'indirizzo_residenza' => 'nullable|string|max:255',
        ]);
        $user->update($validated);
        return redirect()->route('user.dashboard')->with('success', 'Profilo aggiornato con successo!');
    }

    public function editPassword()
    {
        $locale = app()->getLocale();
        return view($locale . '.user.profile.password', compact('locale'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('profile.password.edit')->with('success', 'Password aggiornata con successo!');
    }

}
