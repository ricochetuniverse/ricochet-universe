<?php

declare(strict_types=1);

namespace App\Policies;

use App\User;

class LevelSetPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }
}
