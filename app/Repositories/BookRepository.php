<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\BookRepositoryInterface;
use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class BookRepository implements BookRepositoryInterface
{
    public function paginate(
        array $filters,
        string $sortField,
        string $sortDirection,
        int $perPage
    ): LengthAwarePaginator {
        $query = Book::query();

        if (!empty($filters['genre'])) {
            $query->where('genre', $filters['genre']);
        }

        if (!empty($filters['author'])) {
            $query->where('author', 'like', "%{$filters['author']}%");
        }

        if (isset($filters['available']) && $filters['available'] === true) {
            $query->where('available_copies', '>', 0);
        }

        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    public function findOrFail(int $id): Book
    {
        return Book::findOrFail($id);
    }

    public function create(array $attributes): Book
    {
        return Book::create($attributes);
    }

    public function update(Book $book, array $attributes): Book
    {
        $book->update($attributes);

        return $book->fresh();
    }

    public function delete(Book $book): void
    {
        $book->delete();
    }

    public function search(string $queryString): Collection
    {
        return Book::query()
            ->where(function ($query) use ($queryString): void {
                $term = "%{$queryString}%";
                $query->where('title', 'like', $term)
                    ->orWhere('author', 'like', $term)
                    ->orWhere('description', 'like', $term);
            })
            ->get();
    }

    public function incrementCopies(Book $book): Book
    {
        $book->increment('available_copies');

        return $book->fresh();
    }

    public function decrementCopies(Book $book): Book
    {
        $book->decrement('available_copies');

        return $book->fresh();
    }
}
