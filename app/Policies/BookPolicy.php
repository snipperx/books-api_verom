<?php
// app/Policies/BookPolicy.php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class BookPolicy
{
    /**
     * Admins bypass every policy method.
     * Returning null falls through to the individual methods.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Anyone may list books — including unauthenticated visitors.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Anyone may view a single book.
     */
    public function view(?User $user, Book $book): bool
    {
        return true;
    }

    public function create(User $user): Response
    {
        return $user->hasPermission(Permission::BooksCreate)
            ? Response::allow()
            : Response::deny(
                message: 'You do not have permission to create books.',
                code: 'INSUFFICIENT_PERMISSIONS',
            );
    }

    public function update(User $user, Book $book): Response
    {
        return $user->hasPermission(Permission::BooksUpdate)
            ? Response::allow()
            : Response::deny(
                message: 'You do not have permission to update books.',
                code: 'INSUFFICIENT_PERMISSIONS',
            );
    }

    public function delete(User $user, Book $book): Response
    {
        return $user->hasPermission(Permission::BooksDelete)
            ? Response::allow()
            : Response::deny(
                message: 'You do not have permission to delete books.',
                code: 'INSUFFICIENT_PERMISSIONS',
            );
    }

    public function restore(User $user, Book $book): Response
    {
        return $user->hasPermission(Permission::BooksRestore)
            ? Response::allow()
            : Response::deny(
                message: 'You do not have permission to restore deleted books.',
                code: 'INSUFFICIENT_PERMISSIONS',
            );
    }

    public function borrow(User $user, Book $book): Response
    {
        if (!$user->hasPermission(Permission::BooksBorrow)) {
            return Response::deny(
                message: 'You do not have permission to borrow books.',
                code: 'INSUFFICIENT_PERMISSIONS',
            );
        }

        if (!$book->isAvailable()) {
            return Response::deny(
                message: "Book with ID [{$book->id}] has no available copies.",
                code: 'BOOK_NOT_AVAILABLE',
            );
        }

        return Response::allow();
    }

    public function return(User $user, Book $book): Response
    {
        return $user->hasPermission(Permission::BooksReturn)
            ? Response::allow()
            : Response::deny(
                message: 'You do not have permission to return books.',
                code: 'INSUFFICIENT_PERMISSIONS',
            );
    }
}
