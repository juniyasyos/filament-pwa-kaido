<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects;

use InvalidArgumentException;

final readonly class SplashSize
{
    private const VALID_SIZES = [
        '640x1136',
        '750x1334',
        '828x1792',
        '1125x2436',
        '1242x2208',
        '1242x2688',
        '1536x2048',
        '1668x2224',
        '1668x2388',
        '2048x2732',
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
        return 'pwa_splash_' . $this->value;
    }

    public function defaultPath(): string
    {
        return "/images/icons/splash-{$this->value}.png";
    }

    private function validate(): void
    {
        if (!in_array($this->value, self::VALID_SIZES, true)) {
            throw new InvalidArgumentException("Invalid splash size: {$this->value}");
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
