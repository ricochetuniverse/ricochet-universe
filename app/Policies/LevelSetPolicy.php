<?php

declare(strict_types=1);

namespace App\Policies;

use App\LevelSet;
use App\User;
use Illuminate\Auth\Access\Response;

class LevelSetPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, LevelSet $levelSet): Response
    {
        return $user?->is_admin ? Response::allow() : Response::denyAsNotFound();
    }
}
