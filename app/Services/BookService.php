<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\BookRepositoryInterface;
use App\Contracts\BookServiceInterface;
use App\Events\BookBorrowed;
use App\Events\BookReturned;
use App\Exceptions\BookNotAvailableException;
use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class BookService implements BookServiceInterface
{
    /**
     * @param BookRepositoryInterface $bookRepository
     */
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository,
    ) {}

    /**
     * @param array $filters
     * @param string $sort
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function listBooks(array $filters, string $sort, int $perPage): LengthAwarePaginator
    {
        ['field' => $sortField, 'direction' => $sortDirection] = $this->parseSortParameter($sort);

        $perPage = min($perPage, 100);

        return $this->bookRepository->paginate($filters, $sortField, $sortDirection, $perPage);
    }

    /**
     * @param int $id
     * @return Book
     */
    public function findBook(int $id): Book
    {
        return $this->bookRepository->findOrFail($id);
    }

    /**
     * @param array $attributes
     * @return Book
     */
    public function createBook(array $attributes): Book
    {
        $book = $this->bookRepository->create($attributes);

        Cache::tags(['books'])->flush();

        return $book;
    }

    /**
     * @param Book $book
     * @param array $attributes
     * @return Book
     */
    public function updateBook(Book $book, array $attributes): Book
    {
        $updated = $this->bookRepository->update($book, $attributes);

        Cache::tags(['books'])->flush();

        return $updated;
    }

    /**
     * @param Book $book
     * @return void
     */
    public function deleteBook(Book $book): void
    {
        $this->bookRepository->delete($book);

        Cache::tags(['books'])->flush();
    }

    /**
     * @param string $query
     * @return Collection
     */
    public function searchBooks(string $query): Collection
    {
        return $this->bookRepository->search($query);
    }

    /**
     * @param Book $book
     * @param int $userId
     * @return Book
     * @throws \Throwable
     */
    public function borrowBook(Book $book, int $userId): Book
    {
        if (!$book->isAvailable()) {
            throw new BookNotAvailableException($book->id);
        }

        $updatedBook = DB::transaction(function () use ($book): Book {
            return $this->bookRepository->decrementCopies($book);
        });

        BookBorrowed::dispatch($updatedBook, $userId);

        Cache::tags(['books'])->flush();

        return $updatedBook;
    }


    /**
     * @param Book $book
     * @param int $userId
     * @return Book
     * @throws \Throwable
     */
    public function returnBook(Book $book, int $userId): Book
    {
        $updatedBook = DB::transaction(function () use ($book): Book {
            return $this->bookRepository->incrementCopies($book);
        });

        BookReturned::dispatch($updatedBook, $userId);

        Cache::tags(['books'])->flush();

        return $updatedBook;
    }

    /**
     * @return array{field: string, direction: string}
     */
    private function parseSortParameter(string $sort): array
    {
        $allowedFields = ['title', 'author', 'published_at', 'created_at', 'available_copies'];

        $direction = 'asc';
        $field     = $sort;

        if (str_starts_with($sort, '-')) {
            $direction = 'desc';
            $field     = substr($sort, 1);
        }

        if (!in_array($field, $allowedFields, true)) {
            $field = 'created_at';
        }

        return ['field' => $field, 'direction' => $direction];
    }
}
