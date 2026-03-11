<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BookServiceInterface
{
    public function listBooks(array $filters, string $sort, int $perPage): LengthAwarePaginator;

    public function findBook(int $id): Book;

    public function createBook(array $attributes): Book;

    public function updateBook(Book $book, array $attributes): Book;

    public function deleteBook(Book $book): void;

    public function searchBooks(string $query): Collection;

    public function borrowBook(Book $book, int $userId): Book;

    public function returnBook(Book $book, int $userId): Book;
}
