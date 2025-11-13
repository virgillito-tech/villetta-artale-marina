<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContattiController;
use App\Http\Controllers\PrenotazioneController;
use App\Http\Controllers\RecensioneController;
use App\Http\Controllers\PrezzoGiornalieroController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PagamentiController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ServiziController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
// Rotta pubblica per l'esportazione del calendario iCal per Booking.com
Route::get('/ical/export/villetta-artale-sync.ics', 
    [\App\Http\Controllers\PrenotazioneController::class, 'exportIcal'])
    ->name('ical.export');

// ==================================
// HOME, GALLERIA, SERVIZI, CONTATTI
// ==================================
Route::get('/', [HomeController::class, 'index'])
    ->middleware('web', \App\Http\Middleware\SetLocale::class)
    ->name('home');

Route::get('/galleria', [GalleriaController::class, 'index'])
    ->middleware('web', \App\Http\Middleware\SetLocale::class)
    ->name('galleria');

Route::get('/servizi', [ServiziController::class, 'index'])
    ->middleware('web', \App\Http\Middleware\SetLocale::class)
    ->name('servizi');

Route::get('/contatti', [ContattiController::class, 'index'])
    ->middleware('web', \App\Http\Middleware\SetLocale::class)
    ->name('contatti');

// ==================================
// RECENSIONI
// ==================================
Route::get('/recensioni', [RecensioneController::class, 'index'])
    ->middleware('web', \App\Http\Middleware\SetLocale::class)
    ->name('recensioni.index');
Route::post('/recensioni', [RecensioneController::class, 'store'])->name('recensioni.store');

// ==================================
// PROFILO E DASHBOARD (UTENTE AUTENTICATO)
// ==================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard utente
    //Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('profile.dashboard');
    // Dashboard utente
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])
     ->middleware('web', \App\Http\Middleware\SetLocale::class)
    ->name('user.dashboard');


    // Visualizza e modifica profilo
    Route::get('/profile/edit', [ProfileController::class, 'edit']) ->middleware('web', \App\Http\Middleware\SetLocale::class)->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update']) ->middleware('web', \App\Http\Middleware\SetLocale::class)->name('profile.update');

    // Modifica password utente
    Route::get('/profile/password', [ProfileController::class, 'editPassword']) ->middleware('web', \App\Http\Middleware\SetLocale::class)->name('profile.password.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']) ->middleware('web', \App\Http\Middleware\SetLocale::class)->name('profile.password.update');


    // Prenotazioni utente
    Route::get('/le-mie-prenotazioni', [PrenotazioneController::class, 'userPrenotazioni']) ->middleware('web', \App\Http\Middleware\SetLocale::class)->name('user.prenotazioni');

    // Ricerca disponibilità e prenotazioni
    Route::get('/ricerca-disponibilita', [PrenotazioneController::class, 'search'])->middleware('web', \App\Http\Middleware\SetLocale::class)->name('prenotazioni.search');
    Route::post('/prenota', [PrenotazioneController::class, 'store'])->middleware('web', \App\Http\Middleware\SetLocale::class)->name('prenotazioni.store');

    // PayPal
    Route::get('/paypal/success', [PagamentiController::class, 'paypalSuccess'])->name('paypal.success');
    Route::get('/paypal/cancel', [PagamentiController::class, 'paypalCancel'])->name('paypal.cancel');
    // Pagamenti
    Route::post('/pagamento/checkout', [PagamentiController::class, 'checkout'])->middleware('web', \App\Http\Middleware\SetLocale::class)->name('pagamento.checkout');
    Route::get('/pagamento/success', [PagamentiController::class, 'success'])->middleware('web', \App\Http\Middleware\SetLocale::class)->name('pagamento.success');
    Route::get('/pagamento/cancel', [PagamentiController::class, 'cancel'])->middleware('web', \App\Http\Middleware\SetLocale::class)->name('pagamento.cancel');
    // Annulla prenotazione
    Route::post('/prenotazioni/{prenotazione}/annulla', 
        [\App\Http\Controllers\PrenotazioneController::class, 'annulla'])
        ->name('prenotazioni.annulla')
        ->middleware('auth');

});

// ==================================
// ADMIN (middleware role:admin)
// ==================================
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->middleware('web', \App\Http\Middleware\SetLocale::class)
        ->name('dashboard');

    // Prenotazioni
    // URL: /admin/prenotazioni -> Nome: admin.prenotazioni.index
    Route::get('/prenotazioni', [PrenotazioneController::class, 'adminIndex'])
        ->middleware('web', \App\Http\Middleware\SetLocale::class)
        ->name('prenotazioni.index');
    
    // URL: /admin/prenotazioni/{id} -> Nome: admin.prenotazioni.show
    Route::get('/prenotazioni/{id}', [PrenotazioneController::class, 'show'])
        ->middleware('web', \App\Http\Middleware\SetLocale::class)
        ->name('prenotazioni.show');
    
    // URL: /admin/prenotazioni/{id}/stato -> Nome: admin.prenotazioni.updateStato
    Route::patch('/prenotazioni/{id}/stato', [PrenotazioneController::class, 'updateStato'])
        ->middleware('web', \App\Http\Middleware\SetLocale::class)
        ->name('prenotazioni.updateStato');
    
    // 🎯 ECCO LA ROTTA CORRETTA 🎯
    // URL: /admin/prenotazioni/{prenotazione} -> Nome: admin.prenotazioni.destroy
    Route::delete('/prenotazioni/{prenotazione}', [PrenotazioneController::class, 'adminDestroy'])
        ->middleware('web', \App\Http\Middleware\SetLocale::class)
        ->name('prenotazioni.destroy');

    // URL: /admin/prenotazione -> Nome: admin.prenotazione.store
    Route::post('/prenotazione', [PrenotazioneController::class, 'store'])
        ->middleware('web', \App\Http\Middleware\SetLocale::class)
        ->name('prenotazione.store');

    // URL: /admin/calendario -> Nome: admin.calendario
    Route::get('/calendario', [PrenotazioneController::class, 'indexCalendario'])
        ->middleware('web', \App\Http\Middleware\SetLocale::class)
        ->name('calendario');

    // Prezzi giornalieri
    Route::prefix('prezzi')->name('prezzi.')->group(function () {
        Route::get('/', [PrezzoGiornalieroController::class, 'index'])->middleware('web', \App\Http\Middleware\SetLocale::class)->name('index');
        Route::post('/', [PrezzoGiornalieroController::class, 'store'])->middleware('web', \App\Http\Middleware\SetLocale::class)->name('store');
        Route::put('/{data}', [PrezzoGiornalieroController::class, 'update'])->middleware('web', \App\Http\Middleware\SetLocale::class)->name('update');
        Route::delete('/{data}', [PrezzoGiornalieroController::class, 'destroy'])->middleware('web', \App\Http\Middleware\SetLocale::class)->name('destroy');
        Route::get('/json', [PrezzoGiornalieroController::class, 'getPrezziJson'])->middleware('web', \App\Http\Middleware\SetLocale::class)->name('json');
    });
});

// ==================================
// SOCIAL LOGIN
// ==================================
Route::get('auth/google', [SocialController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [SocialController::class, 'handleGoogleCallback']);

// ==================================
// PASSWORD RESET / VERIFICA EMAIL
// ==================================
Route::get('forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

Route::get('reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/email/verification-notification', [AuthenticatedSessionController::class, 'sendEmailVerificationNotification'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');


// ==================================
// AUTENTICAZIONE STANDARD (login, register ecc.)
// ==================================

require __DIR__.'/auth.php';
