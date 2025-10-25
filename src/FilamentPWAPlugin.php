<?php

declare(strict_types=1);

/**
 * Backward Compatibility Layer
 * 
 * This file provides aliases for backward compatibility with existing code.
 * These will be deprecated in future versions.
 * 
 * @deprecated Use Juniyasyos\FilamentPWA\Presentation\Filament\FilamentPWAPlugin instead
 */

namespace Juniyasyos\FilamentPWA;

use Juniyasyos\FilamentPWA\Presentation\Filament\FilamentPWAPlugin as NewFilamentPWAPlugin;

/**
 * @deprecated Use Juniyasyos\FilamentPWA\Presentation\Filament\FilamentPWAPlugin instead
 */
class FilamentPWAPlugin extends NewFilamentPWAPlugin
{
    // This class extends the new implementation for backward compatibility
}
