<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Domain\PWA\Entities;

use Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects\AppName;
use Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects\Color;

final class PWAConfiguration
{
    /**
     * @param Icon[] $icons
     * @param SplashScreen[] $splashScreens
     * @param Shortcut[] $shortcuts
     */
    public function __construct(
        private ?AppName $appName = null,
        private ?AppName $shortName = null,
        private string $startUrl = '/',
        private string $display = 'standalone',
        private ?Color $themeColor = null,
        private ?Color $backgroundColor = null,
        private ?Color $statusBarColor = null,
        private string $orientation = 'portrait',
        private array $icons = [],
        private array $splashScreens = [],
        private array $shortcuts = []
    ) {
    }

    public static function create(): self
    {
        return new self();
    }

    public function withAppName(?AppName $appName): self
    {
        $this->appName = $appName;
        return $this;
    }

    public function withShortName(?AppName $shortName): self
    {
        $this->shortName = $shortName;
        return $this;
    }

    public function withStartUrl(string $startUrl): self
    {
        $this->startUrl = $startUrl;
        return $this;
    }

    public function withDisplay(string $display): self
    {
        $this->display = $display;
        return $this;
    }

    public function withThemeColor(?Color $themeColor): self
    {
        $this->themeColor = $themeColor;
        return $this;
    }

    public function withBackgroundColor(?Color $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }

    public function withStatusBarColor(?Color $statusBarColor): self
    {
        $this->statusBarColor = $statusBarColor;
        return $this;
    }

    public function withOrientation(string $orientation): self
    {
        $this->orientation = $orientation;
        return $this;
    }

    public function addIcon(Icon $icon): self
    {
        $this->icons[] = $icon;
        return $this;
    }

    public function addSplashScreen(SplashScreen $splashScreen): self
    {
        $this->splashScreens[] = $splashScreen;
        return $this;
    }

    public function addShortcut(Shortcut $shortcut): self
    {
        $this->shortcuts[] = $shortcut;
        return $this;
    }

    public function toManifest(): array
    {
        $manifest = [
            'name' => $this->appName?->value(),
            'short_name' => $this->shortName?->value(),
            'start_url' => asset($this->startUrl),
            'display' => $this->display,
            'theme_color' => $this->themeColor?->value() ?? '#000000',
            'background_color' => $this->backgroundColor?->value() ?? '#ffffff',
            'orientation' => $this->orientation,
            'status_bar' => $this->statusBarColor?->value() ?? '#000000',
        ];

        if (!empty($this->icons)) {
            $manifest['icons'] = array_map(
                fn (Icon $icon) => $icon->toArray(),
                $this->icons
            );
        }

        if (!empty($this->splashScreens)) {
            $splash = [];
            foreach ($this->splashScreens as $splashScreen) {
                $splash = array_merge($splash, $splashScreen->toArray());
            }
            $manifest['splash'] = $splash;
        }

        if (!empty($this->shortcuts)) {
            $manifest['shortcuts'] = array_map(
                fn (Shortcut $shortcut) => $shortcut->toArray(),
                $this->shortcuts
            );
        }

        return $manifest;
    }

    public function appName(): ?AppName
    {
        return $this->appName;
    }

    public function shortName(): ?AppName
    {
        return $this->shortName;
    }

    public function icons(): array
    {
        return $this->icons;
    }

    public function splashScreens(): array
    {
        return $this->splashScreens;
    }

    public function shortcuts(): array
    {
        return $this->shortcuts;
    }
}
