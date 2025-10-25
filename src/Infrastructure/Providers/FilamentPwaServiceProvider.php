<?php

declare(strict_types=1);

namespace Juniyasyos\FilamentPWA\Infrastructure\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Juniyasyos\FilamentPWA\Application\Services\ManifestGenerator;
use Juniyasyos\FilamentPWA\Application\Services\PWAConfigurationBuilder;
use Juniyasyos\FilamentPWA\Application\Services\ServiceWorkerGenerator;
use Juniyasyos\FilamentPWA\Application\UseCases\GenerateManifestUseCase;
use Juniyasyos\FilamentPWA\Application\UseCases\GenerateServiceWorkerUseCase;
use Juniyasyos\FilamentPWA\Application\UseCases\InstallPWAUseCase;
use Juniyasyos\FilamentPWA\Domain\PWA\Repositories\PWASettingsRepositoryInterface;
use Juniyasyos\FilamentPWA\Infrastructure\Console\FilamentPwaInstallCommand;
use Juniyasyos\FilamentPWA\Infrastructure\Persistence\SpatieSettingsPWARepository;
use Juniyasyos\FilamentPWA\Policies\PWASettingsPolicy;
use Juniyasyos\FilamentPWA\Presentation\Filament\Pages\PWASettingsPage;
use Juniyasyos\FilamentPWA\Settings\PWASettings;

final class FilamentPwaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register Config
        $this->mergeConfigFrom(__DIR__.'/../../../config/filament-pwa.php', 'filament-pwa');

        // Register Domain Layer - Repository Interface
        $this->app->bind(
            PWASettingsRepositoryInterface::class,
            SpatieSettingsPWARepository::class
        );

        // Register Application Layer - Services
        $this->app->singleton(PWAConfigurationBuilder::class, function ($app) {
            return new PWAConfigurationBuilder(
                $app->make(PWASettingsRepositoryInterface::class)
            );
        });

        $this->app->singleton(ManifestGenerator::class, function ($app) {
            return new ManifestGenerator(
                $app->make(PWAConfigurationBuilder::class)
            );
        });

        $this->app->singleton(ServiceWorkerGenerator::class, function ($app) {
            return new ServiceWorkerGenerator(
                $app->make(PWAConfigurationBuilder::class)
            );
        });

        // Register Application Layer - Use Cases
        $this->app->singleton(GenerateManifestUseCase::class, function ($app) {
            return new GenerateManifestUseCase(
                $app->make(ManifestGenerator::class)
            );
        });

        $this->app->singleton(GenerateServiceWorkerUseCase::class, function ($app) {
            return new GenerateServiceWorkerUseCase(
                $app->make(ServiceWorkerGenerator::class)
            );
        });

        $this->app->singleton(InstallPWAUseCase::class, function ($app) {
            return new InstallPWAUseCase(
                $app->make(PWASettingsRepositoryInterface::class),
                $app->make(GenerateServiceWorkerUseCase::class)
            );
        });

        // Register Commands
        $this->commands([
            FilamentPwaInstallCommand::class,
        ]);
    }

    public function boot(): void
    {
        // Publish Config
        $this->publishes([
            __DIR__.'/../../../config/filament-pwa.php' => config_path('filament-pwa.php'),
        ], 'filament-pwa-config');

        // Register views
        $this->loadViewsFrom(__DIR__.'/../../../resources/views', 'filament-pwa');

        // Publish Views
        $this->publishes([
            __DIR__.'/../../../resources/views' => resource_path('views/vendor/filament-pwa'),
        ], 'filament-pwa-views');

        // Register Translations
        $this->loadTranslationsFrom(__DIR__.'/../../../resources/lang', 'filament-pwa');

        // Publish Translations
        $this->publishes([
            __DIR__.'/../../../resources/lang' => base_path('lang/vendor/filament-pwa'),
        ], 'filament-pwa-lang');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../../../routes/web.php');

        // Register Blade directive
        Blade::directive('filamentPWA', function () {
            return "<?php echo view('filament-pwa::meta', ['config' => app(\\Juniyasyos\\FilamentPWA\\Application\\UseCases\\GenerateManifestUseCase::class)->execute()])->render(); ?>";
        });

        // Register policy
        Gate::policy(PWASettingsPage::class, PWASettingsPolicy::class);
    }
}
