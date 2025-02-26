<?php

namespace Juniyasyos\FilamentPWA\Policies;

use App\Models\User;

class PWASettingsPolicy
{
    public function view(User $user): bool
    {
        return $user->can('view_pwa_settings');
    }

    public function update(User $user): bool
    {
        return $user->can('manage_pwa_settings');
    }
}
