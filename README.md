# Filament PWA

[![Latest Stable Version](https://poser.pugx.org/juniyasyos/filament-pwa-kaido/version.svg)](https://packagist.org/packages/juniyasyos/filament-pwa-kaido)
[![License](https://poser.pugx.org/juniyasyos/filament-pwa-kaido/license.svg)](https://packagist.org/packages/juniyasyos/filament-pwa-kaido)
[![Downloads](https://poser.pugx.org/juniyasyos/filament-pwa-kaido/d/total.svg)](https://packagist.org/packages/juniyasyos/filament-pwa-kaido)

A Progressive Web App (PWA) plugin for FilamentPHP v4 with a complete settings panel, built with **Domain-Driven Design (DDD)** architecture for maximum maintainability.

## 🏗️ Architecture

This package follows **Domain-Driven Design** principles with clear separation of concerns:

- **Domain Layer**: Pure business logic (Entities, Value Objects, Repository Interfaces)
- **Application Layer**: Use cases and application services
- **Infrastructure Layer**: Framework implementations (Laravel, Filament)
- **Presentation Layer**: UI components (Filament Pages)

👉 See [DDD_ARCHITECTURE.md](DDD_ARCHITECTURE.md) for detailed architecture documentation.

## Requirements

- PHP 8.2 or higher
- Laravel 11.x
- Filament v4.x

## Installation

Install the package via Composer:

```bash
composer require juniyasyos/filament-pwa-kaido
```

### Install Settings Table

Publish and migrate the settings table:

```bash
php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations"
php artisan filament-settings-hub:install 
```

### Install PWA Assets

Run the installation command:

```bash
php artisan filament-pwa:install
```

This will:
- Create the PWA settings migration
- Copy default PWA icons to your public directory
- Generate the service worker file

### Register the Plugin

Register the plugin in your panel provider (e.g., `app/Providers/Filament/AdminPanelProvider.php`):

```php
use Juniyasyos\FilamentPWA\FilamentPWAPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(FilamentPWAPlugin::make())
        ->plugin(\Juniyasyos\FilamentSettingsHub\FilamentSettingsHubPlugin::make());
}
```

## Usage

### Using the Blade Directive

Add the PWA meta tags to any Blade view by placing this directive before the closing `</head>` tag:

```blade
<!DOCTYPE html>
<html>
<head>
    <!-- Your other head elements -->
    @filamentPWA
</head>
<body>
    <!-- Your content -->
</body>
</html>
```

### Accessing the Settings Page

Once installed, you can access the PWA settings page from your Filament Settings Hub panel to configure:

- App name and short name
- Start URL
- Theme colors and background
- Display mode and orientation
- PWA icons (multiple sizes)
- Splash screens (iOS)
- App shortcuts

## Publishing Assets

### Publish Configuration

```bash
php artisan vendor:publish --tag="filament-pwa-config"
```

### Publish Views

```bash
php artisan vendor:publish --tag="filament-pwa-views"
```

### Publish Translations

```bash
php artisan vendor:publish --tag="filament-pwa-lang"
```

## Configuration

The config file allows you to customize:

```php
return [
    // Middleware applied to PWA routes
    'middlewares' => [],
    
    // Enable/disable PWA routes
    'allow_routes' => true,
];
```

## Available Routes

When routes are enabled, the following endpoints are available:

- `/manifest.json` - PWA manifest file
- `/offline` - Offline fallback page

## Customization

### Icons

The plugin supports multiple icon sizes:
- 72x72, 96x96, 128x128, 144x144
- 152x152, 192x192, 384x384, 512x512

### Splash Screens

Supports iOS splash screens for various device sizes:
- 640x1136, 750x1334, 828x1792
- 1125x2436, 1242x2208, 1242x2688
- 1536x2048, 1668x2224, 1668x2388, 2048x2732

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](mailto:info@3x1.io) - Original Author
- [Ahmad Ilyas](https://wa.me/+6285732431396) - Maintainer

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
