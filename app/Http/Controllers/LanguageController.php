<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        // Controlla se la lingua è supportata
        if (!in_array($locale, ['it', 'en'])) {
            $locale = config('app.locale'); // fallback
        }

        // Imposta la lingua in sessione
        session(['locale' => $locale]);
        app()->setLocale($locale);

        // Torna alla pagina precedente
        return redirect()->back();
    }
}
