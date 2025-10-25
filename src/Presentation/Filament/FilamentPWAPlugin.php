<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Presentation\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Juniyasyos\FilamentPWA\Application\UseCases\GenerateManifestUseCase;
use Juniyasyos\FilamentPWA\Presentation\Filament\Pages\PWASettingsPage;
use Juniyasyos\FilamentSettingsHub\Facades\FilamentSettingsHub;
use Juniyasyos\FilamentSettingsHub\FilamentSettingsHubPlugin;
use Juniyasyos\FilamentSettingsHub\Services\Contracts\SettingHold;

final class FilamentPWAPlugin implements Plugin
{
    protected bool $allowPWASettings = true;

    protected bool $allowShield = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function allowPWASettings(bool $allow = true): static
    {
        $this->allowPWASettings = $allow;

        return $this;
    }

    public function allowShield(bool $allow = true): static
    {
        $this->allowShield = $allow;

        return $this;
    }

    public function isShield(): bool
    {
        return $this->allowShield;
    }

    public function getId(): string
    {
        return 'filament-pwa';
    }

    public function register(Panel $panel): void
    {
        if ($this->isShield()) {
            $panel
                ->pages([
                    PWASettingsPage::class,
                ])
                ->plugin(FilamentSettingsHubPlugin::make()->allowShield());
        }
    }

    public function boot(Panel $panel): void
    {
        // Register render hook for PWA meta tags
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): string => $this->renderPWAMeta(),
        );

        // Register settings hub integration
        if ($this->isShield()) {
            FilamentSettingsHub::register([
                SettingHold::make()
                    ->label('filament-pwa::messages.settings.title')
                    ->icon('heroicon-o-sparkles')
                    ->page(PWASettingsPage::class)
                    ->description('filament-pwa::messages.settings.description'),
            ]);
        }
    }

    private function renderPWAMeta(): string
    {
        try {
            $generateManifest = app(GenerateManifestUseCase::class);
            $config = $generateManifest->execute();

            return view('filament-pwa::meta', ['config' => $config])->render();
        } catch (\Exception $e) {
            logger()->error('Failed to render PWA meta: ' . $e->getMessage());
            return '';
        }
    }
}
