<?php

declare(strict_types=1);

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\Response;

class ModPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): Response
    {
        return $user?->is_admin ? Response::allow() : Response::denyAsNotFound();
    }
}
