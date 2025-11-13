<?php

namespace App\Http\Controllers;

use App\Models\Prenotazione;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'confermate' => Prenotazione::where('stato', 'confermata')->count(),
            'in_attesa' => Prenotazione::where('stato', 'in attesa')->count(),
            'annullate' => Prenotazione::where('stato', 'annullata')->count(),
            'totale_mese' => Prenotazione::whereMonth('created_at', now()->month)->count(),
        ];

        $prenotazioni_recenti = Prenotazione::with([])
            ->latest()
            ->take(5)
            ->get();

        //return view('admin.dashboard', compact('stats', 'prenotazioni_recenti'));
        $locale = app()->getLocale(); // recupera lingua corrente (es: 'it' o 'en')

        return view($locale . '.admin.dashboard', compact('stats', 'prenotazioni_recenti', 'locale'));
    }
}
