<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $path = public_path('file/nazioni.csv');
        $nazioni = [];

        if (file_exists($path)) {
            $rows = array_map(function($line) {
                return str_getcsv($line, ';'); // <-- usa ; come separatore
            }, file($path));

            // esempio: CSV ha colonne [sigla;nome]
            foreach ($rows as $row) {
                if (!empty($row[1])) {
                    $nazioni[] = $row[1]; // solo il nome (Italia, Francia, ecc.)
                }
            }
        }

        return view('auth.register', compact('nazioni'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
         $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'codice_fiscale' => [
                Rule::requiredIf(fn () => request('nazionalita') === 'Italia'),
                'nullable',
                'string',
                'max:16'
            ],
            'nome' => ['required', 'string', 'max:255'],
            'cognome' => ['required', 'string', 'max:255'],
            'data_nascita' => ['required', 'date', 'before:today'],
            'luogo_nascita' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:20'],
            'indirizzo_residenza' => ['required', 'string', 'max:255'],
            'nazionalita' => ['required', 'string', 'max:255'],
            'sesso' => ['required', 'in:M,F,Altro'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'username' => $request->username,
            'codice_fiscale' => strtoupper($request->codice_fiscale),
            'nome' => $request->nome,
            'cognome' => $request->cognome,
            'data_nascita' => $request->data_nascita,
            'luogo_nascita' => $request->luogo_nascita,
            'telefono' => $request->telefono,
            'indirizzo_residenza' => $request->indirizzo_residenza,
            'nazionalita' => $request->nazionalita,
            'sesso' => $request->sesso,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('welcome', absolute: false));
    }
}
