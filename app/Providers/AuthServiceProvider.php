<?php
// app/Providers/AuthServiceProvider.php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Book;
use App\Models\User;
use App\Policies\BookPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Book::class => BookPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        $this->registerRoleGates();
        $this->registerPermissionGates();
    }

    // ─── Role Gates ───────────────────────────────────────────────────────────

    /**
     * Register a gate for every role in the enum.
     *
     * Usage:  Gate::check('role:admin', $user)
     *         $request->user()->can('role:librarian')
     *         @can('role:member')
     */
    private function registerRoleGates(): void
    {
        foreach (Role::cases() as $role) {
            Gate::define(
                "role:{$role->value}",
                fn (User $user): bool => $user->hasRole($role),
            );
        }

        // Convenience gate — checks if user has ANY of the given roles
        // Usage: Gate::check('any-role', [Role::Admin, Role::Librarian])
        Gate::define(
            'any-role',
            fn (User $user, Role ...$roles): bool => $user->hasAnyRole(...$roles),
        );
    }

    // ─── Permission Gates ─────────────────────────────────────────────────────

    /**
     * Register a gate for every permission in the enum.
     *
     * Usage:  Gate::check('books:create')
     *         $request->user()->can('books:create')
     *         @can('books:create')
     */
    private function registerPermissionGates(): void
    {
        foreach (Permission::cases() as $permission) {
            Gate::define(
                $permission->value,
                fn (User $user): bool => $user->hasPermission($permission),
            );
        }
    }
}
