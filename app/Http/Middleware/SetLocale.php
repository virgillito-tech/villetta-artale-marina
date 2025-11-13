<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // dd('Middleware SetLocale eseguito');
        $locale = session('locale', config('app.locale'));
        \Log::info("SetLocale Middleware: current locale is " . $locale);

        app()->setLocale($locale);

        return $next($request);
    }

}
