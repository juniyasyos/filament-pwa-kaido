<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects;

use InvalidArgumentException;

final readonly class IconSize
{
    private const VALID_SIZES = [
        '72x72',
        '96x96',
        '128x128',
        '144x144',
        '152x152',
        '192x192',
        '384x384',
        '512x512',
    ];

    public function __construct(
        private string $value
    ) {
        $this->validate();
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public static function all(): array
    {
        return self::VALID_SIZES;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function propertyName(): string
    {
        return 'pwa_icons_' . $this->value;
    }

    public function defaultPath(): string
    {
        return "/images/icons/icon-{$this->value}.png";
    }

    private function validate(): void
    {
        if (!in_array($this->value, self::VALID_SIZES, true)) {
            throw new InvalidArgumentException("Invalid icon size: {$this->value}");
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
