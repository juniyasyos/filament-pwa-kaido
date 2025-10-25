<?php

declare(strict_types=1);

/**
 * Backward Compatibility Layer
 * 
 * @deprecated Use Juniyasyos\FilamentPWA\Infrastructure\Providers\FilamentPwaServiceProvider instead
 */

namespace Juniyasyos\FilamentPWA;

use Juniyasyos\FilamentPWA\Infrastructure\Providers\FilamentPwaServiceProvider as NewServiceProvider;

class FilamentPwaServiceProvider extends NewServiceProvider
{
    // This class extends the new implementation for backward compatibility
}
