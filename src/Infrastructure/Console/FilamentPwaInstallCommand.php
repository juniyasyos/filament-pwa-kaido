<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Infrastructure\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Juniyasyos\FilamentPWA\Application\UseCases\GenerateServiceWorkerUseCase;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;

final class FilamentPwaInstallCommand extends Command
{
    use RunCommand;

    protected $signature = 'filament-pwa:install';

    protected $description = 'Install Filament PWA package and publish assets';

    public function __construct(
        private readonly GenerateServiceWorkerUseCase $generateServiceWorker
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Installing Filament PWA...');
        $this->call('optimize:clear');

        $this->installMigration();
        $this->runMigrations();
        $this->copyPublicAssets();
        $this->generateServiceWorker();

        $this->info('Filament PWA installed successfully!');

        return self::SUCCESS;
    }

    private function installMigration(): void
    {
        $dbPath = File::files(database_path('migrations'));
        $exists = false;

        foreach ($dbPath as $path) {
            if (str($path->getFilename())->contains('_pwa_settings.php')) {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $stubPath = __DIR__.'/../../../database/migrations/pwa_settings.php.stub';
            $databasePath = database_path('migrations/'.date('Y_m_d_His', time()).'_pwa_settings.php');

            File::copy($stubPath, $databasePath);
            $this->info('Migration file created successfully.');
        }
    }

    private function runMigrations(): void
    {
        $this->info('Running migrations...');
        Artisan::call('migrate');
    }

    private function copyPublicAssets(): void
    {
        $this->info('Copying public assets...');
        File::copyDirectory(
            __DIR__.'/../../../resources/images',
            public_path('images')
        );
    }

    private function generateServiceWorker(): void
    {
        $this->info('Generating service worker...');
        
        try {
            $this->generateServiceWorker->execute(public_path('serviceworker.js'));
            $this->info('Service worker generated successfully.');
        } catch (\Exception $e) {
            $this->warn('Failed to generate service worker: ' . $e->getMessage());
        }
    }
}
