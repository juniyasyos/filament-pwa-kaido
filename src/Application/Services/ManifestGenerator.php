<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Application\Services;

use Juniyasyos\FilamentPWA\Domain\PWA\Entities\PWAConfiguration;

final readonly class ManifestGenerator
{
    public function __construct(
        private PWAConfigurationBuilder $configBuilder
    ) {
    }

    public function generate(): array
    {
        $config = $this->configBuilder->build();
        
        return $config->toManifest();
    }

    public function generateForView(): array
    {
        return $this->generate();
    }
}
