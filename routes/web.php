<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContattiController;
use App\Http\Controllers\PrenotazioneController;
use App\Http\Controllers\RecensioneController;
use App\Http\Controllers\PrezzoGiornalieroController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PagamentiController;
use App\Http\Controllers\Auth\SocialController;


Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    //Route::get('/prenotazioni', [PrenotazioneController::class, 'index'])->name('prenotazioni.index');
    Route::post('/prenotazione', [PrenotazioneController::class, 'store'])->name('prenotazione.store');
    Route::patch('/prenotazioni/{id}/stato', [PrenotazioneController::class, 'updateStato'])->name('prenotazioni.updateStato');
    Route::get('/prenotazioni/{id}', [PrenotazioneController::class, 'show'])->name('prenotazioni.show');
    Route::get('/admin/prenotazioni', [PrenotazioneController::class, 'index'])->name('admin.prenotazioni.index');
    Route::get('/prenotazioni', [PrenotazioneController::class, 'adminIndex'])->name('prenotazioni.index');
    Route::get('/calendario', [PrenotazioneController::class, 'indexCalendario'])->name('calendario');
});

// RECENSIONI
Route::get('/recensioni', [RecensioneController::class, 'index'])->name('recensioni.index');
Route::post('/recensioni', [RecensioneController::class, 'store'])->name('recensioni.store');

// HOME
Route::get('/', [HomeController::class, 'index'])->name('home');

// GALLERIA
Route::get('/galleria', function () {
    return view('galleria');
})->name('galleria');

// SERVIZI
Route::get('/servizi', function () {
    return view('servizi');
})->name('servizi');

// CONTATTI
Route::get('/contatti', [ContattiController::class, 'index'])->name('contatti');

// DASHBOARD (protetta)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware(['auth'])->group(function () {

    // Visualizza il profilo
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    // Modifica il profilo (form)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Aggiorna le informazioni del profilo
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Aggiorna la password
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

     Route::get('/le-mie-prenotazioni', [App\Http\Controllers\PrenotazioneController::class, 'userPrenotazioni'])
        ->name('user.prenotazioni');

});



// ROTTE USER (per utenti normali autenticati)
Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');
    
    Route::get('/prenotazioni', function () {
        $prenotazioni = \App\Models\Prenotazione::where('email', Auth::user()->email)->get();
        return view('user.prenotazioni', compact('prenotazioni'));
    })->name('prenotazioni');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/ricerca-disponibilita', [PrenotazioneController::class, 'search'])->name('prenotazioni.search');
    Route::post('/prenota', [PrenotazioneController::class, 'store'])->name('prenotazioni.store');
});


Route::prefix('prezzi')->name('prezzi.')->group(function () {
    Route::get('/', [PrezzoGiornalieroController::class, 'index'])->name('index');
    Route::post('/', [PrezzoGiornalieroController::class, 'store'])->name('store');
    Route::put('/{data}', [PrezzoGiornalieroController::class, 'update'])->name('update');
    Route::delete('/{data}', [PrezzoGiornalieroController::class, 'destroy'])->name('destroy');
    Route::get('/json', [PrezzoGiornalieroController::class, 'getPrezziJson'])->name('json');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/pagamento/checkout', [PagamentiController::class, 'checkout'])->name('pagamento.checkout');
    Route::get('/pagamento/success', [PagamentiController::class, 'success'])->name('pagamento.success');
    Route::get('/pagamento/cancel', [PagamentiController::class, 'cancel'])->name('pagamento.cancel');
});


Route::get('auth/google', [SocialController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [SocialController::class, 'handleGoogleCallback']);

Route::get('auth/apple', [SocialController::class, 'redirectToApple']);
Route::get('auth/apple/callback', [SocialController::class, 'handleAppleCallback']);




// AUTENTICAZIONE
require __DIR__.'/auth.php';