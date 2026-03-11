<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BookRepositoryInterface
{
    public function paginate(array $filters, string $sortField, string $sortDirection, int $perPage): LengthAwarePaginator;

    public function findOrFail(int $id): Book;

    public function create(array $attributes): Book;

    public function update(Book $book, array $attributes): Book;

    public function delete(Book $book): void;

    public function search(string $query): Collection;

    public function incrementCopies(Book $book): Book;

    public function decrementCopies(Book $book): Book;
}
