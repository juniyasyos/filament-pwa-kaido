<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Domain\PWA\Entities;

final readonly class Shortcut
{
    public function __construct(
        private string $name,
        private string $description,
        private string $url,
        private ?string $icon = null
    ) {
    }

    public static function create(
        string $name,
        string $description,
        string $url,
        ?string $icon = null
    ): self {
        return new self($name, $description, $url, $icon);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            description: $data['description'] ?? '',
            url: $data['url'] ?? '/',
            icon: $data['icon'] ?? null
        );
    }

    public function toArray(): array
    {
        $data = [
            'name' => trans($this->name),
            'description' => trans($this->description),
            'url' => $this->url,
        ];

        if ($this->icon) {
            $data['icons'] = [
                [
                    'src' => $this->icon,
                    'type' => $this->getImageType(),
                    'sizes' => '72x72',
                    'purpose' => 'any',
                ],
            ];
        }

        return $data;
    }

    private function getImageType(): string
    {
        $extension = pathinfo($this->icon, PATHINFO_EXTENSION);
        return 'image/' . strtolower($extension ?: 'png');
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function icon(): ?string
    {
        return $this->icon;
    }
}
