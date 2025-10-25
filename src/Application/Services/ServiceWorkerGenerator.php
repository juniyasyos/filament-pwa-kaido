<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Application\Services;

use Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects\IconSize;

final readonly class ServiceWorkerGenerator
{
    public function __construct(
        private PWAConfigurationBuilder $configBuilder
    ) {
    }

    public function generate(): string
    {
        $config = $this->configBuilder->build();
        $icons = [];

        foreach ($config->icons() as $icon) {
            $icons[] = '    "' . $icon->path()->value() . '"';
        }

        return $this->getTemplate($icons);
    }

    private function getTemplate(array $icons): string
    {
        $iconsList = implode(",\n", $icons);

        return <<<JS
// Service Worker
const CACHE_NAME = 'pwa-cache-v1';
const urlsToCache = [
    '/',
    '/offline',
{$iconsList}
];

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                if (response) {
                    return response;
                }
                return fetch(event.request);
            }
        )
    );
});
JS;
    }

    public function save(string $path): void
    {
        $content = $this->generate();
        file_put_contents($path, $content);
    }
}
