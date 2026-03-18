<?php
// app/Policies/UserPolicy.php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class UserPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): Response
    {
        return $user->hasPermission(Permission::UsersView)
            ? Response::allow()
            : Response::deny(
                message: 'You do not have permission to view users.',
                code: 'INSUFFICIENT_PERMISSIONS',
            );
    }

    public function view(User $user, User $target): Response
    {
        // Users may always view their own profile
        if ($user->id === $target->id) {
            return Response::allow();
        }

        return $user->hasPermission(Permission::UsersView)
            ? Response::allow()
            : Response::deny(
                message: 'You do not have permission to view this user.',
                code: 'INSUFFICIENT_PERMISSIONS',
            );
    }

    public function manageRoles(User $user, User $target): Response
    {
        // Prevent privilege escalation — no one assigns Admin to others
        if ($user->id === $target->id) {
            return Response::deny(
                message: 'You cannot modify your own role.',
                code: 'SELF_ROLE_MODIFICATION',
            );
        }

        return $user->hasPermission(Permission::UsersManage)
            ? Response::allow()
            : Response::deny(
                message: 'You do not have permission to manage user roles.',
                code: 'INSUFFICIENT_PERMISSIONS',
            );
    }
}
