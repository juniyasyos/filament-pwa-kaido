<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Presentation\Filament\Pages;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Support\Facades\File;
use Juniyasyos\FilamentPWA\Application\UseCases\GenerateServiceWorkerUseCase;
use Juniyasyos\FilamentPWA\Settings\PWASettings;
use Juniyasyos\FilamentSettingsHub\Traits\HasSettingsBreadcrumbs;
use Juniyasyos\FilamentSettingsHub\Traits\UseShield;

final class PWASettingsPage extends SettingsPage
{
    use UseShield;
    use HasSettingsBreadcrumbs;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = PWASettings::class;

    protected static ?string $slug = 'pwa-settings-page';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return trans('filament-pwa::messages.settings.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(['default' => 2])
                    ->schema([
                        $this->getGeneralSection(),
                        $this->getStyleSection(),
                        $this->getIconsSection(),
                        $this->getSplashSection(),
                        $this->getShortcutsSection(),
                    ]),
            ]);
    }

    protected function getGeneralSection(): Section
    {
        return Section::make(trans('filament-pwa::messages.sections.general'))
            ->collapsible()
            ->schema([
                TextInput::make('pwa_app_name')
                    ->label(trans('filament-pwa::messages.form.pwa_app_name'))
                    ->columnSpan(2)
                    ->helperText(config('filament-settings-hub.show_hint') ? 'setting("pwa_app_name")' : null),
                TextInput::make('pwa_short_name')
                    ->label(trans('filament-pwa::messages.form.pwa_short_name'))
                    ->columnSpan(2)
                    ->helperText(config('filament-settings-hub.show_hint') ? 'setting("pwa_short_name")' : null),
                TextInput::make('pwa_start_url')
                    ->label(trans('filament-pwa::messages.form.pwa_start_url'))
                    ->columnSpan(2)
                    ->helperText(config('filament-settings-hub.show_hint') ? 'setting("pwa_start_url")' : null),
            ]);
    }

    protected function getStyleSection(): Section
    {
        return Section::make(trans('filament-pwa::messages.sections.style'))
            ->collapsible()
            ->collapsed()
            ->schema([
                ColorPicker::make('pwa_background_color')
                    ->default('#ffffff')
                    ->label(trans('filament-pwa::messages.form.pwa_background_color'))
                    ->columnSpan(2)
                    ->helperText(config('filament-settings-hub.show_hint') ? 'setting("pwa_background_color")' : null),
                ColorPicker::make('pwa_status_bar')
                    ->default('#000000')
                    ->label(trans('filament-pwa::messages.form.pwa_status_bar'))
                    ->columnSpan(2)
                    ->helperText(config('filament-settings-hub.show_hint') ? 'setting("pwa_status_bar")' : null),
                ColorPicker::make('pwa_theme_color')
                    ->default('#000000')
                    ->label(trans('filament-pwa::messages.form.pwa_theme_color'))
                    ->columnSpan(2)
                    ->helperText(config('filament-settings-hub.show_hint') ? 'setting("pwa_theme_color")' : null),
                TextInput::make('pwa_display')
                    ->label(trans('filament-pwa::messages.form.pwa_display'))
                    ->columnSpan(2)
                    ->helperText(config('filament-settings-hub.show_hint') ? 'setting("pwa_display")' : null),
                TextInput::make('pwa_orientation')
                    ->label(trans('filament-pwa::messages.form.pwa_orientation'))
                    ->columnSpan(2)
                    ->helperText(config('filament-settings-hub.show_hint') ? 'setting("pwa_orientation")' : null),
            ]);
    }

    protected function getIconsSection(): Section
    {
        $iconSizes = ['72x72', '96x96', '128x128', '144x144', '152x152', '192x192', '384x384', '512x512'];
        $fields = [];

        foreach ($iconSizes as $size) {
            $fields[] = FileUpload::make("pwa_icons_{$size}")
                ->acceptedFileTypes(['image/png'])
                ->label(trans("filament-pwa::messages.form.pwa_icons_{$size}"))
                ->columnSpan(2)
                ->helperText(config('filament-settings-hub.show_hint') ? "setting(\"pwa_icons_{$size}\")" : null);
        }

        return Section::make(trans('filament-pwa::messages.sections.icons'))
            ->collapsible()
            ->collapsed()
            ->schema($fields);
    }

    protected function getSplashSection(): Section
    {
        $splashSizes = [
            '640x1136', '750x1334', '828x1792', '1125x2436', '1242x2208',
            '1242x2688', '1536x2048', '1668x2224', '1668x2388', '2048x2732',
        ];
        $fields = [];

        foreach ($splashSizes as $size) {
            $fields[] = FileUpload::make("pwa_splash_{$size}")
                ->acceptedFileTypes(['image/png'])
                ->label(trans("filament-pwa::messages.form.pwa_splash_{$size}"))
                ->columnSpan(2)
                ->helperText(config('filament-settings-hub.show_hint') ? "setting(\"pwa_splash_{$size}\")" : null);
        }

        return Section::make(trans('filament-pwa::messages.sections.splash'))
            ->collapsible()
            ->collapsed()
            ->schema($fields);
    }

    protected function getShortcutsSection(): Section
    {
        return Section::make(trans('filament-pwa::messages.sections.shortcuts'))
            ->collapsible()
            ->collapsed()
            ->schema([
                Repeater::make('pwa_shortcuts')
                    ->schema([
                        TextInput::make('name')
                            ->label(trans('filament-pwa::messages.form.pwa_shortcuts_name')),
                        Textarea::make('description')
                            ->label(trans('filament-pwa::messages.form.pwa_shortcuts_description')),
                        TextInput::make('url')
                            ->url()
                            ->label(trans('filament-pwa::messages.form.pwa_shortcuts_url')),
                        FileUpload::make('icon')
                            ->image()
                            ->label(trans('filament-pwa::messages.form.pwa_shortcuts_icon')),
                    ])
                    ->label(trans('filament-pwa::messages.form.pwa_shortcuts'))
                    ->columnSpan(2)
                    ->helperText(config('filament-settings-hub.show_hint') ? 'setting("pwa_shortcuts")' : null),
            ]);
    }

    protected function afterSave(): void
    {
        try {
            $generateServiceWorker = app(GenerateServiceWorkerUseCase::class);
            $generateServiceWorker->execute(public_path('serviceworker.js'));
        } catch (\Exception $e) {
            // Log error but don't fail the save
            logger()->error('Failed to generate service worker: ' . $e->getMessage());
        }
    }
}
