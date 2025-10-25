<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects;

final readonly class IconPath
{
    public function __construct(
        private ?string $value,
        private string $defaultPath
    ) {
    }

    public static function create(?string $value, string $defaultPath): self
    {
        return new self($value, $defaultPath);
    }

    public function value(): string
    {
        return $this->value 
            ? '/storage/' . $this->value 
            : $this->defaultPath;
    }

    public function hasCustomIcon(): bool
    {
        return $this->value !== null;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
