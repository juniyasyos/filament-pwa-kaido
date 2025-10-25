<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Application\UseCases;

use Juniyasyos\FilamentPWA\Application\Services\ManifestGenerator;

final readonly class GenerateManifestUseCase
{
    public function __construct(
        private ManifestGenerator $manifestGenerator
    ) {
    }

    public function execute(): array
    {
        return $this->manifestGenerator->generate();
    }
}
