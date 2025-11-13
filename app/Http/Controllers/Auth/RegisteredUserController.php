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
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

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
                return str_getcsv($line, ';'); // usa ; come separatore
            }, file($path));

            foreach ($rows as $row) {
                if (!empty($row[1])) {
                    $nazioni[] = $row[1]; // solo il nome
                }
            }
        }

        $locale = session('locale', config('app.locale'));

        return view($locale . '.auth.register', compact('nazioni', 'locale'));
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

         DB::beginTransaction();
        try {
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

            DB::commit();

            event(new Registered($user));
            \Log::info('Dopo event Registered');

            \Log::info('Prima Auth::login');
            Auth::login($user);
            \Log::info('Dopo Auth::login');

            return redirect()->route('verification.notice')->with('success', 'Registrazione completata, verifica la tua email!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors(['error' => 'Errore nella registrazione: ' . $e->getMessage()]);
        }
    }

    
}
