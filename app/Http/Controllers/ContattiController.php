<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContattiController extends Controller
{
    public function index()
{
    $apiKey = env('OPENWEATHER_API_KEY');
    $city = 'Mascali,it';
    $weather = null;
    $forecast = null;

    if ($apiKey) {
        try {
            // Primo: meteo attuale con coordinate
            $currentResponse = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'it',
            ]);

            if ($currentResponse->successful()) {
                $weather = $currentResponse->json();

                // Prendo lat e lon
                $lat = $weather['coord']['lat'];
                $lon = $weather['coord']['lon'];

                // Secondo: One Call per previsioni giornaliere
                $forecastResponse = Http::get('https://api.openweathermap.org/data/2.5/onecall', [
                    'lat' => $lat,
                    'lon' => $lon,
                    'exclude' => 'minutely,hourly,alerts,current',
                    'appid' => $apiKey,
                    'units' => 'metric',
                    'lang' => 'it',
                ]);

                if ($forecastResponse->successful()) {
                    $forecast = $forecastResponse->json();
                }
            } else {
                Log::warning("OpenWeather risposta non OK: {$currentResponse->status()}");
            }
        } catch (\Exception $e) {
            Log::error("Errore chiamata OpenWeather: " . $e->getMessage());
        }
    } else {
        Log::warning("OPENWEATHER_API_KEY non impostata in .env");
    }

    return view('contatti', compact('weather', 'forecast'));
}
}