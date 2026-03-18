<?php
// app/Enums/Role.php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Permission;

enum Role: string
{
    case Admin     = 'admin';
    case Librarian = 'librarian';
    case Member    = 'member';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Every permission this role is granted.
     *
     * This is the single source of truth for role-permission mapping.
     * No database table required.
     *
     * @return list<Permission>
     */
    public function permissions(): array
    {
        return match($this) {
            self::Admin => Permission::cases(),

            self::Librarian => [
                Permission::BooksCreate,
                Permission::BooksUpdate,
                Permission::BooksBorrow,
                Permission::BooksReturn,
                Permission::UsersView,
            ],

            self::Member => [
                Permission::BooksBorrow,
                Permission::BooksReturn,
            ],
        };
    }

    public function hasPermission(Permission $permission): bool
    {
        return in_array($permission, $this->permissions(), strict: true);
    }
}
