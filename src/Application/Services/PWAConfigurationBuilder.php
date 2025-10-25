<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Application\Services;

use Juniyasyos\FilamentPWA\Domain\PWA\Entities\Icon;
use Juniyasyos\FilamentPWA\Domain\PWA\Entities\PWAConfiguration;
use Juniyasyos\FilamentPWA\Domain\PWA\Entities\Shortcut;
use Juniyasyos\FilamentPWA\Domain\PWA\Entities\SplashScreen;
use Juniyasyos\FilamentPWA\Domain\PWA\Repositories\PWASettingsRepositoryInterface;
use Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects\AppName;
use Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects\Color;
use Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects\IconPath;
use Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects\IconSize;
use Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects\SplashSize;

final readonly class PWAConfigurationBuilder
{
    public function __construct(
        private PWASettingsRepositoryInterface $repository
    ) {
    }

    public function build(): PWAConfiguration
    {
        $config = PWAConfiguration::create();

        // Set basic info
        if ($appName = $this->repository->getAppName()) {
            $config->withAppName(AppName::fromNullable($appName));
        }

        if ($shortName = $this->repository->getShortName()) {
            $config->withShortName(AppName::fromNullable($shortName));
        }

        $config->withStartUrl($this->repository->getStartUrl() ?? '/');
        $config->withDisplay($this->repository->getDisplay() ?? 'standalone');
        $config->withOrientation($this->repository->getOrientation() ?? 'portrait');

        // Set colors
        $config->withThemeColor(
            Color::fromNullable($this->repository->getThemeColor(), '#000000')
        );
        $config->withBackgroundColor(
            Color::fromNullable($this->repository->getBackgroundColor(), '#ffffff')
        );
        $config->withStatusBarColor(
            Color::fromNullable($this->repository->getStatusBarColor(), '#000000')
        );

        // Build icons
        foreach (IconSize::all() as $size) {
            $iconSize = IconSize::from($size);
            $iconPath = IconPath::create(
                $this->repository->getIcon($size),
                $iconSize->defaultPath()
            );
            $config->addIcon(Icon::create($iconSize, $iconPath));
        }

        // Build splash screens
        foreach (SplashSize::all() as $size) {
            $splashSize = SplashSize::from($size);
            $splashPath = IconPath::create(
                $this->repository->getSplash($size),
                $splashSize->defaultPath()
            );
            $config->addSplashScreen(SplashScreen::create($splashSize, $splashPath));
        }

        // Build shortcuts
        if ($shortcuts = $this->repository->getShortcuts()) {
            foreach ($shortcuts as $shortcutData) {
                $config->addShortcut(Shortcut::fromArray($shortcutData));
            }
        }

        return $config;
    }
}
