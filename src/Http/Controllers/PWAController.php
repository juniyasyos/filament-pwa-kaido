<?php

namespace Juniyasyos\FilamentPWA\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Juniyasyos\FilamentPWA\Services\ManifestService;

class PWAController
{
    public function manifest(): JsonResponse
    {
        return response()->json(ManifestService::generate());
    }

    public function offline(): View
    {
        return view('filament-pwa::offline');
    }
}
