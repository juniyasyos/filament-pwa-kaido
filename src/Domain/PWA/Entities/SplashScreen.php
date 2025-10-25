<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Domain\PWA\Entities;

use Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects\IconPath;
use Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects\SplashSize;

final readonly class SplashScreen
{
    public function __construct(
        private SplashSize $size,
        private IconPath $path
    ) {
    }

    public static function create(SplashSize $size, IconPath $path): self
    {
        return new self($size, $path);
    }

    public function size(): SplashSize
    {
        return $this->size;
    }

    public function path(): IconPath
    {
        return $this->path;
    }

    public function toArray(): array
    {
        return [
            $this->size->value() => $this->path->value(),
        ];
    }
}
