<?php

namespace Juniyasyos\FilamentPWA\Services;

use Juniyasyos\FilamentPWA\Settings\PWASettings;

class ManifestService
{
    public static function generate(): array
    {
        $setting = new PWASettings();

        $basicManifest = [
            'name' => $setting->pwa_app_name,
            'short_name' => $setting->pwa_short_name,
            'start_url' => asset($setting->pwa_start_url ?? '/'),
            'display' => $setting->pwa_display ?? 'standalone',
            'theme_color' => $setting->pwa_theme_color ?? '#000000',
            'background_color' => $setting->pwa_background_color ?? '#ffffff',
            'orientation' => $setting->pwa_orientation ?? 'portrait',
            'status_bar' => $setting->pwa_status_bar ?? '#000000',
            'splash' => static::getSplashScreens($setting),
            'icons' => static::getIcons($setting),
        ];

        if ($setting->pwa_shortcuts && is_array($setting->pwa_shortcuts)) {
            $basicManifest['shortcuts'] = static::getShortcuts($setting->pwa_shortcuts);
        }

        return $basicManifest;
    }

    protected static function getSplashScreens(PWASettings $setting): array
    {
        $splashSizes = [
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

        $splash = [];

        foreach ($splashSizes as $size) {
            $property = 'pwa_splash_'.$size;
            $splash[$size] = $setting->$property
                ? '/storage/'.$setting->$property
                : "/images/icons/splash-{$size}.png";
        }

        return $splash;
    }

    protected static function getIcons(PWASettings $setting): array
    {
        $iconSizes = [
            '72x72',
            '96x96',
            '128x128',
            '144x144',
            '152x152',
            '192x192',
            '384x384',
            '512x512',
        ];

        $icons = [];

        foreach ($iconSizes as $size) {
            $property = 'pwa_icons_'.$size;
            $src = $setting->$property
                ? '/storage/'.$setting->$property
                : "/images/icons/icon-{$size}.png";

            $type = static::getImageType($src);

            $icons[] = [
                'src' => $src,
                'type' => $type,
                'sizes' => $size,
                'purpose' => 'any',
            ];
        }

        return $icons;
    }

    protected static function getShortcuts(array $shortcuts): array
    {
        $result = [];

        foreach ($shortcuts as $shortcut) {
            $shortcutData = [
                'name' => trans($shortcut['name'] ?? ''),
                'description' => trans($shortcut['description'] ?? ''),
                'url' => $shortcut['url'] ?? '/',
            ];

            if (isset($shortcut['icon']) && $shortcut['icon']) {
                $type = static::getImageType($shortcut['icon']);
                
                $shortcutData['icons'] = [
                    [
                        'src' => $shortcut['icon'],
                        'type' => $type,
                        'sizes' => '72x72',
                        'purpose' => 'any',
                    ],
                ];
            }

            $result[] = $shortcutData;
        }

        return $result;
    }

    protected static function getImageType(string $path): string
    {
        $storagePath = storage_path('app/public/'.ltrim($path, '/storage/'));
        
        if (file_exists($storagePath)) {
            $extension = pathinfo($storagePath, PATHINFO_EXTENSION);
            return 'image/'.strtolower($extension);
        }

        // Fallback to path extension
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        return 'image/'.strtolower($extension ?: 'png');
    }
}
