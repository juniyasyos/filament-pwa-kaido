# Filament PWA

[![Latest Stable Version](https://poser.pugx.org/juniyasyos/filament-pwa-kaido/version.svg)](https://packagist.org/packages/juniyasyos/filament-pwa-kaido)
[![License](https://poser.pugx.org/juniyasyos/filament-pwa-kaido/license.svg)](https://packagist.org/packages/juniyasyos/filament-pwa-kaido)
[![Downloads](https://poser.pugx.org/juniyasyos/filament-pwa-kaido/d/total.svg)](https://packagist.org/packages/juniyasyos/filament-pwa-kaido)

get a PWA feature on your FilamentPHP app with settings from panel

## Installation

```bash
composer require juniyasyos/filament-pwa-kaido
```

now you need to publish and migrate settings table

```bash
php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations"
php artisan filament-settings-hub:install 
```

after install your package please run this command

```bash
php artisan filament-pwa:install
```

finally reigster the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\Juniyasyos\FilamentPWA\FilamentPWAPlugin::make())
```


## Use Directive

you can use directive to allow PWA on none-FilamentPHP pages, just add this directive to your blade file on top of `</head>`

```html
@filamentPWA
```

## Publish Assets

```bash
php artisan vendor:publish --tag="filament-pwa-lang"
php artisan vendor:publish --tag="filament-pwa-views"
php artisan vendor:publish --tag="filament-pwa-lang"
```

you can publish config file by use this command


```bash
php artisan vendor:publish --tag="filament-pwa-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-pwa-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-pwa-lang"
```


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Ahmad Ilyas](https://wa.me/+6285732431396)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
