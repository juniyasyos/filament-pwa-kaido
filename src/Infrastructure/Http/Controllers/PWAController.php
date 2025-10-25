<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Juniyasyos\FilamentPWA\Application\UseCases\GenerateManifestUseCase;

final readonly class PWAController
{
    public function __construct(
        private GenerateManifestUseCase $generateManifestUseCase
    ) {
    }

    public function manifest(): JsonResponse
    {
        $manifest = $this->generateManifestUseCase->execute();
        
        return response()->json($manifest);
    }

    public function offline(): View
    {
        return view('filament-pwa::offline');
    }
}
