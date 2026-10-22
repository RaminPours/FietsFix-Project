<?php

use App\Http\Controllers\AfsprakenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WinkelController;
use App\Http\Controllers\BestellingController;
use App\Http\Controllers\WinkelmandController;
use App\Http\Controllers\AfrekenenController;
use App\Http\Controllers\PersoneelController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AfsprakenController::class, 'index'])->name('home');
Route::get('/contact', [AfsprakenController::class, 'contact'])->name('contact');
// Iedereen kan fietsen bekijken. Bestellen kan na het inloggen.
Route::get('/winkel', [WinkelController::class, 'index'])->name('winkel.index');
Route::get('/accessoires', [WinkelController::class, 'accessoires'])->name('winkel.accessoires');
Route::get('/winkel/{product}', [WinkelController::class, 'show'])->name('winkel.show');
// Ook bezoekers kunnen een mandje vullen. Alleen het afronden vereist inloggen.
Route::get('/winkelmand', [WinkelmandController::class, 'index'])->name('winkelmand.index');
Route::post('/winkelmand/{product}', [WinkelmandController::class, 'toevoegen'])->name('winkelmand.toevoegen');
Route::patch('/winkelmand/{product}', [WinkelmandController::class, 'wijzigen'])->name('winkelmand.wijzigen');
Route::delete('/winkelmand/{product}', [WinkelmandController::class, 'verwijderen'])->name('winkelmand.verwijderen');
Route::get('/fietsonderhoud', [AfsprakenController::class, 'fietsonderhoud'])
    ->name('fietsonderhoud');
Route::get('/merken', [AfsprakenController::class, 'merken'])
    ->name('merken');

Route::middleware('auth')->group(function () {
    Route::get('/proefrit', [AfsprakenController::class, 'proefritCreate'])->name('proefrit.create');
    Route::post('/proefrit', [AfsprakenController::class, 'proefritStore'])->name('proefrit.store');
    Route::get('/afrekenen', [AfrekenenController::class, 'create'])->name('afrekenen.create');
    Route::post('/afrekenen', [AfrekenenController::class, 'store'])->name('afrekenen.store');
    Route::get('/bestellingen', [BestellingController::class, 'index'])->name('bestellingen.index');
    Route::patch('/bestellingen/{id}/annuleren', [BestellingController::class, 'annuleren'])->name('bestellingen.annuleren');
    Route::get('/winkel/{product}/bestellen', [BestellingController::class, 'create'])->name('bestellingen.create');
    Route::post('/winkel/{product}/bestellen', [BestellingController::class, 'store'])->name('bestellingen.store');
    Route::get('/dashboard', [AfsprakenController::class, 'dashboard'])
        ->name('dashboard');
    Route::post('/afspraken', [AfsprakenController::class, 'store'])
        ->name('afspraken.store');
    Route::get('/afspraken', [AfsprakenController::class, 'dashboard'])
        ->name('afspraken.index');
    Route::get('/afspraken/create', [AfsprakenController::class, 'create'])
        ->name('afspraken.create');
    Route::delete('/afspraken/{id}', [AfsprakenController::class, 'delete'])
        ->name('afspraken.delete');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::middleware(['auth', 'personeel'])->prefix('personeel')->name('personeel.')->group(function () {
    Route::get('/', [PersoneelController::class, 'index'])->name('index');
    Route::get('/voorraad', [PersoneelController::class, 'voorraad'])->name('voorraad');
    Route::patch('/voorraad/{product}', [PersoneelController::class, 'voorraadAanvullen'])->name('voorraad.aanvullen');
    Route::patch('/voorraad/{product}/verlagen', [PersoneelController::class, 'voorraadVerlagen'])->name('voorraad.verlagen');
    Route::get('/bestellingen', [PersoneelController::class, 'bestellingen'])->name('bestellingen');
    Route::patch('/bestellingen/{bestelling}', [PersoneelController::class, 'bestellingStatusBijwerken'])->name('bestellingen.status');
    Route::get('/planning', [PersoneelController::class, 'planning'])->name('planning');
});

require __DIR__.'/auth.php';
