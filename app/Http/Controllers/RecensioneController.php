<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recensione;
use Illuminate\Support\Facades\Auth;

class RecensioneController extends Controller
{
    public function index()
    {
        // Prendi tutte le recensioni, più recenti prima
        $recensioni = Recensione::latest()->get();

        // Passa la variabile alla view
        return view('recensioni', compact('recensioni'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return back()->with('warning', 'Devi essere loggato per lasciare una recensione.');
        }

        // Validazione dati
        $request->validate([
            'nome' => 'required|string|max:255',
            'contenuto' => 'required|string',
            'voto' => 'required|integer|min:1|max:5',
        ]);

        // Crea nuova recensione
        Recensione::create([
            'nome' => $request->nome,
            'contenuto' => $request->contenuto,
            'voto' => $request->voto,
        ]);

        // Redirect con messaggio di successo
        return redirect()->route('recensioni.index')->with('success', 'Grazie per la tua recensione!');
    }
}