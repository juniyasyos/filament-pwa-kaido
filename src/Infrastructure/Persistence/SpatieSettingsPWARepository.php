<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Infrastructure\Persistence;

use Juniyasyos\FilamentPWA\Domain\PWA\Repositories\PWASettingsRepositoryInterface;
use Juniyasyos\FilamentPWA\Settings\PWASettings;

final class SpatieSettingsPWARepository implements PWASettingsRepositoryInterface
{
    public function __construct(
        private PWASettings $settings
    ) {
    }

    public function getAppName(): ?string
    {
        return $this->settings->pwa_app_name;
    }

    public function getShortName(): ?string
    {
        return $this->settings->pwa_short_name;
    }

    public function getStartUrl(): ?string
    {
        return $this->settings->pwa_start_url;
    }

    public function getDisplay(): ?string
    {
        return $this->settings->pwa_display;
    }

    public function getOrientation(): ?string
    {
        return $this->settings->pwa_orientation;
    }

    public function getThemeColor(): ?string
    {
        return $this->settings->pwa_theme_color;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->settings->pwa_background_color;
    }

    public function getStatusBarColor(): ?string
    {
        return $this->settings->pwa_status_bar;
    }

    public function getIcon(string $size): ?string
    {
        $property = 'pwa_icons_' . $size;
        return $this->settings->$property ?? null;
    }

    public function getSplash(string $size): ?string
    {
        $property = 'pwa_splash_' . $size;
        return $this->settings->$property ?? null;
    }

    public function getShortcuts(): ?array
    {
        return $this->settings->pwa_shortcuts;
    }

    public function save(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this->settings, $key)) {
                $this->settings->$key = $value;
            }
        }
        
        $this->settings->save();
    }
}
