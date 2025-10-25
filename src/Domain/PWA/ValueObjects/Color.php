<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects;

use InvalidArgumentException;

final readonly class Color
{
    public function __construct(
        private string $value
    ) {
        $this->validate();
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public static function fromNullable(?string $value, string $default = '#000000'): self
    {
        return new self($value ?? $default);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    private function validate(): void
    {
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $this->value)) {
            throw new InvalidArgumentException("Invalid color format: {$this->value}");
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
