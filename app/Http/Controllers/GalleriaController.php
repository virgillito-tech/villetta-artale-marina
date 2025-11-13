<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GalleriaController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();
        return view($locale . '.galleria', compact('locale'));
    }
}
