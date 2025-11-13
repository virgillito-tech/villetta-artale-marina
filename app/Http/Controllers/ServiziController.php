<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiziController extends Controller
{
    public function index()
    {
        $locale = session('locale', config('app.locale'));

        return view($locale . '.servizi', compact('locale'));
    }
}
