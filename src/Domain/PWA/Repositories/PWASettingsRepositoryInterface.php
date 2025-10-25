<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Domain\PWA\Repositories;

interface PWASettingsRepositoryInterface
{
    public function getAppName(): ?string;

    public function getShortName(): ?string;

    public function getStartUrl(): ?string;

    public function getDisplay(): ?string;

    public function getOrientation(): ?string;

    public function getThemeColor(): ?string;

    public function getBackgroundColor(): ?string;

    public function getStatusBarColor(): ?string;

    public function getIcon(string $size): ?string;

    public function getSplash(string $size): ?string;

    public function getShortcuts(): ?array;

    public function save(array $data): void;
}
