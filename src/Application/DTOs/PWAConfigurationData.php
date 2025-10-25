<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Application\DTOs;

final readonly class PWAConfigurationData
{
    public function __construct(
        public ?string $appName,
        public ?string $shortName,
        public ?string $startUrl,
        public ?string $display,
        public ?string $orientation,
        public ?string $themeColor,
        public ?string $backgroundColor,
        public ?string $statusBarColor,
        public array $icons,
        public array $splashScreens,
        public array $shortcuts
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            appName: $data['app_name'] ?? null,
            shortName: $data['short_name'] ?? null,
            startUrl: $data['start_url'] ?? '/',
            display: $data['display'] ?? 'standalone',
            orientation: $data['orientation'] ?? 'portrait',
            themeColor: $data['theme_color'] ?? null,
            backgroundColor: $data['background_color'] ?? null,
            statusBarColor: $data['status_bar_color'] ?? null,
            icons: $data['icons'] ?? [],
            splashScreens: $data['splash_screens'] ?? [],
            shortcuts: $data['shortcuts'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'app_name' => $this->appName,
            'short_name' => $this->shortName,
            'start_url' => $this->startUrl,
            'display' => $this->display,
            'orientation' => $this->orientation,
            'theme_color' => $this->themeColor,
            'background_color' => $this->backgroundColor,
            'status_bar_color' => $this->statusBarColor,
            'icons' => $this->icons,
            'splash_screens' => $this->splashScreens,
            'shortcuts' => $this->shortcuts,
        ];
    }
}
