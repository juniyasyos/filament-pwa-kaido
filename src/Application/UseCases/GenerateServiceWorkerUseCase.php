<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Application\UseCases;

use Juniyasyos\FilamentPWA\Application\Services\ServiceWorkerGenerator;

final readonly class GenerateServiceWorkerUseCase
{
    public function __construct(
        private ServiceWorkerGenerator $serviceWorkerGenerator
    ) {
    }

    public function execute(string $outputPath): void
    {
        $this->serviceWorkerGenerator->save($outputPath);
    }

    public function getContent(): string
    {
        return $this->serviceWorkerGenerator->generate();
    }
}
