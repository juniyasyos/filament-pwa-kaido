<?php

namespace Juniyasyos\FilamentPWA\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Juniyasyos\FilamentPWA\Settings\PWASettings;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;

class FilamentPwaInstall extends Command
{
    use RunCommand;

    protected $signature = 'filament-pwa:install';

    protected $description = 'Install Filament PWA package and publish assets';

    public function handle(): int
    {
        $this->info('Installing Filament PWA...');
        $this->call('optimize:clear');

        $dbPath = File::files(database_path('migrations'));
        $exists = false;
        
        foreach ($dbPath as $path) {
            if (str($path->getFilename())->contains('_pwa_settings.php')) {
                $exists = true;
                break;
            }
        }

        // Register migrations
        if (! $exists) {
            $stubPath = __DIR__.'/../../database/migrations/pwa_settings.php.stub';
            $databasePath = database_path('migrations/'.date('Y_m_d_His', time()).'_pwa_settings.php');

            File::copy($stubPath, $databasePath);
            $this->info('Migration file created successfully.');
        }

        $this->info('Running migrations...');
        Artisan::call('migrate');

        $this->info('Copying public assets...');
        File::copyDirectory(__DIR__.'/../../resources/images', public_path('images'));

        $this->info('Generating service worker...');
        $this->generateServiceWorker();

        $this->info('Filament PWA installed successfully!');

        return self::SUCCESS;
    }

    protected function generateServiceWorker(): void
    {
        $setting = new PWASettings();
        $jsPath = __DIR__.'/../../resources/js/serviceworker.js';
        
        if (! File::exists($jsPath)) {
            $this->warn('Service worker template not found.');
            return;
        }

        $jsContent = File::get($jsPath);
        $icons = [];

        $iconSizes = ['72x72', '96x96', '128x128', '144x144', '152x152', '192x192', '384x384', '512x512'];
        
        foreach ($iconSizes as $size) {
            $property = 'pwa_icons_'.$size;
            $icon = $setting->$property 
                ? '    "'.'/storage/'.$setting->$property.'"'
                : '    "/images/icons/icon-'.$size.'.png"';
            $icons[] = $icon;
        }

        $value = str($jsContent)
            ->replace('ICONS', collect($icons)->implode(','."\n"))
            ->toString();

        File::put(public_path('serviceworker.js'), $value);
        
        $this->info('Service worker generated successfully.');
    }
}
