<?php

use App\Http\Controllers\BenutzerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumentController;
use App\Http\Controllers\PaechterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StellplatzController;
use App\Http\Controllers\UebernachtungController;
use App\Http\Controllers\VertragController;
use App\Http\Controllers\ZahlungController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Stellplätze
    Route::resource('stellplaetze', StellplatzController::class)
        ->parameters(['stellplaetze' => 'stellplatz']);

    // Pächter
    Route::get('paechter/{paechter}/jahresabrechnung', [PaechterController::class, 'jahresabrechnung'])
        ->name('paechter.jahresabrechnung');
    Route::resource('paechter', PaechterController::class)
        ->parameters(['paechter' => 'paechter']);

    // Verträge
    Route::resource('vertraege', VertragController::class)
        ->parameters(['vertraege' => 'vertrag']);

    // Zahlungen
    Route::resource('zahlungen', ZahlungController::class)
        ->parameters(['zahlungen' => 'zahlung']);
    Route::patch('zahlungen/{zahlung}/bezahlt', [ZahlungController::class, 'alsBezahltMarkieren'])
        ->name('zahlungen.bezahlt');

    // Benutzerverwaltung (nur Admin – Schutz im Controller)
    Route::resource('benutzer', BenutzerController::class)
        ->parameters(['benutzer' => 'benutzer'])
        ->except(['show']);

    // Dokumente
    Route::get('dokumente/{dokument}/download', [DokumentController::class, 'download'])
        ->name('dokumente.download');
    Route::resource('dokumente', DokumentController::class)
        ->parameters(['dokumente' => 'dokument']);

    // Übernachtungen – eigene Routen VOR Resource-Route
    Route::get('uebernachtungen/statistik', [UebernachtungController::class, 'statistik'])
        ->name('uebernachtungen.statistik');
    Route::get('uebernachtungen/kalender', [UebernachtungController::class, 'kalender'])
        ->name('uebernachtungen.kalender');
    Route::get('uebernachtungen/pdf', [UebernachtungController::class, 'exportPdf'])
        ->name('uebernachtungen.pdf');
    Route::resource('uebernachtungen', UebernachtungController::class)
        ->parameters(['uebernachtungen' => 'uebernachtung'])
        ->except(['show']);
});

require __DIR__ . '/auth.php';
