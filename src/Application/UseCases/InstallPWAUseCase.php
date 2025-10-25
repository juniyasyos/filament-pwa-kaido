<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Application\UseCases;

use Juniyasyos\FilamentPWA\Domain\PWA\Repositories\PWASettingsRepositoryInterface;

final readonly class InstallPWAUseCase
{
    public function __construct(
        private PWASettingsRepositoryInterface $repository,
        private GenerateServiceWorkerUseCase $generateServiceWorker
    ) {
    }

    public function execute(): void
    {
        // Copy default icons
        $this->copyDefaultIcons();

        // Generate service worker
        $this->generateServiceWorker->execute(public_path('serviceworker.js'));
    }

    private function copyDefaultIcons(): void
    {
        $sourcePath = __DIR__ . '/../../../resources/images';
        $destinationPath = public_path('images');

        if (is_dir($sourcePath)) {
            $this->copyDirectory($sourcePath, $destinationPath);
        }
    }

    private function copyDirectory(string $source, string $destination): void
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $dir = opendir($source);
        
        while (($file = readdir($dir)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $sourcePath = $source . '/' . $file;
            $destPath = $destination . '/' . $file;

            if (is_dir($sourcePath)) {
                $this->copyDirectory($sourcePath, $destPath);
            } else {
                copy($sourcePath, $destPath);
            }
        }

        closedir($dir);
    }
}
