<?php

use Illuminate\Support\Facades\Route;

if(config('filament-pwa.allow_routes')){
    Route::middleware(config('filament-pwa.middlewares'))->as('pwa.')->group(function()
    {
        Route::get('/manifest.json', [\Juniyasyos\FilamentPWA\Http\Controllers\PWAController::class, 'index'])->name('manifest');
        Route::get('/offline/', [\Juniyasyos\FilamentPWA\Http\Controllers\PWAController::class, 'offline'])->name('offline');
    });
}
