<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Domain\PWA\Entities;

use Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects\IconPath;
use Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects\IconSize;

final readonly class Icon
{
    public function __construct(
        private IconSize $size,
        private IconPath $path,
        private string $type = 'image/png',
        private string $purpose = 'any'
    ) {
    }

    public static function create(IconSize $size, IconPath $path): self
    {
        return new self($size, $path);
    }

    public function toArray(): array
    {
        return [
            'src' => $this->path->value(),
            'type' => $this->type,
            'sizes' => $this->size->value(),
            'purpose' => $this->purpose,
        ];
    }

    public function size(): IconSize
    {
        return $this->size;
    }

    public function path(): IconPath
    {
        return $this->path;
    }

    public function type(): string
    {
        return $this->type;
    }
}
