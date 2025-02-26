<?php

namespace Juniyasyos\FilamentPWA;

use Filament\Panel;
use Filament\Contracts\Plugin;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Filament\Support\Facades\FilamentView;
use Juniyasyos\FilamentPWA\Settings\PWASettings;
use Filament\Support\Concerns\EvaluatesClosures;
use Juniyasyos\FilamentPWA\Services\ManifestService;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Juniyasyos\FilamentPWA\Filament\Pages\PWASettingsPage;
use Juniyasyos\FilamentSettingsHub\FilamentSettingsHubPlugin;
use Juniyasyos\FilamentSettingsHub\Facades\FilamentSettingsHub;
use Juniyasyos\FilamentSettingsHub\Services\Contracts\SettingHold;


class FilamentPWAPlugin implements Plugin
{

    public static bool $allowPWASettings = true;

    public static bool|\Closure $allowShield = true;

    public function allowPWASettings(bool $allow = true): static
    {
        static::$allowPWASettings = $allow;
        return $this;
    }

    public function allowShield(bool $allow = true): static
    {
        static::$allowShield = $allow;
        return $this;
    }

    public function isShield(): bool
    {
        return static::$allowShield;
    }

    public function getId(): string
    {
        return 'filament-pwa';
    }

    public function register(Panel $panel): void
    {
        if ($this->isShield()) {
            $panel->pages([PWASettingsPage::class])
                ->plugin(FilamentSettingsHubPlugin::make())
                ->plugin(FilamentShieldPlugin::make());
        }
    }

    public function boot(Panel $panel): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn() => view('filament-pwa::meta', ['config' => ManifestService::generate()])
        );

        if ($this->isShield()) {
            FilamentSettingsHub::register([
                SettingHold::make()
                    ->label('filament-pwa::messages.settings.title')
                    ->icon('heroicon-o-sparkles')
                    ->page(PWASettingsPage::class)
                    ->description('filament-pwa::messages.settings.description')
                    ->group('filament-settings-hub::messages.group'),
            ]);
        }
    }

    public static function make(): static
    {
        return new static();
    }
}
