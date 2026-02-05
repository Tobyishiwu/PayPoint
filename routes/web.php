<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FundWalletController;
use App\Http\Controllers\Services\{
    AirtimeController,
    DataController,
    ElectricityController,
    CableTvController
};

Route::get('/', fn () => view('welcome'));

Route::middleware(['auth'])->group(function () {

    // --- Core Dashboard & Funding ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/history', [DashboardController::class, 'history'])->name('history');
    Route::get('/fund-wallet', [FundWalletController::class, 'show'])->name('wallet.fund');

    // --- Support ---
    Route::get('/support', fn () => view('support'))->name('support');

    // --- Modular Service Routes ---
    Route::prefix('services')->name('services.')->group(function () {

        // Airtime
        Route::get('/airtime', [AirtimeController::class, 'index'])->name('airtime');
        Route::post('/airtime/process', [AirtimeController::class, 'process'])->name('airtime.process');

        // Data (FIXED: Supports both GET and POST for step 2)
        Route::get('/data', [DataController::class, 'index'])->name('data.index');
        Route::match(['get', 'post'], '/data/select-plan', [DataController::class, 'selectPlan'])->name('data.select');
        Route::post('/data/process', [DataController::class, 'process'])->name('data.process');

        // Electricity (FIXED: Supports both GET and POST for verification step)
        Route::get('/electricity', [ElectricityController::class, 'index'])->name('electricity.index');
        Route::match(['get', 'post'], '/electricity/verify', [ElectricityController::class, 'verifyMeter'])->name('electricity.verify');
        Route::post('/electricity/process', [ElectricityController::class, 'process'])->name('electricity.process');

        // Cable TV (FIXED: Supports both GET and POST for bundle selection)
        Route::get('/cable-tv', [CableTvController::class, 'index'])->name('tv.index');
        Route::match(['get', 'post'], '/cable-tv/select-bundle', [CableTvController::class, 'selectBundle'])->name('tv.select');
        Route::post('/cable-tv/process', [CableTvController::class, 'process'])->name('tv.process');
    });

    // --- Profile & Security Management ---
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
        Route::get('/settings/security', 'security')->name('settings.security');
        Route::post('/settings/update-pin', 'updatePin')->name('settings.update-pin');
    });
});

require __DIR__ . '/auth.php';
