<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Domain\PWA\ValueObjects;

use InvalidArgumentException;

final readonly class AppName
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

    public static function fromNullable(?string $value): ?self
    {
        return $value ? new self($value) : null;
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
        if (empty(trim($this->value))) {
            throw new InvalidArgumentException('App name cannot be empty');
        }

        if (strlen($this->value) > 255) {
            throw new InvalidArgumentException('App name cannot exceed 255 characters');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
