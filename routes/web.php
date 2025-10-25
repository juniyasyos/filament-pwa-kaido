<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Juniyasyos\FilamentPWA\Infrastructure\Http\Controllers\PWAController;

if (config('filament-pwa.allow_routes')) {
    Route::middleware(config('filament-pwa.middlewares'))
        ->as('pwa.')
        ->group(function () {
            Route::get('/manifest.json', [PWAController::class, 'manifest'])->name('manifest');
            Route::get('/offline', [PWAController::class, 'offline'])->name('offline');
        });
}
