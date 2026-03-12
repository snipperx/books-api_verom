<?php

declare(strict_types=1);

use App\Contracts\BookRepositoryInterface;
use App\Exceptions\BookNotAvailableException;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

uses(RefreshDatabase::class);

function makeService(MockInterface $repository): BookService
{
    return new BookService($repository);
}

it('throws BookNotAvailableException when borrowing unavailable book', function (): void {
    $book              = new Book();
    $book->id          = 1;
    $book->available_copies = 0;

    $repository = Mockery::mock(BookRepositoryInterface::class);

    $service = makeService($repository);

    expect(fn () => $service->borrowBook($book, 1))
        ->toThrow(BookNotAvailableException::class);
});

it('parses descending sort parameter correctly', function (): void {
    $repository = Mockery::mock(BookRepositoryInterface::class);
    $repository
        ->shouldReceive('paginate')
        ->withArgs(fn ($filters, $field, $direction) => $field === 'published_at' && $direction === 'desc')
        ->once()
        ->andReturn(new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15));

    $service = makeService($repository);
    $service->listBooks([], '-published_at', 15);
});

it('caps per_page at 100', function (): void {
    $repository = Mockery::mock(BookRepositoryInterface::class);
    $repository
        ->shouldReceive('paginate')
        ->withArgs(fn ($filters, $field, $direction, $perPage) => $perPage === 100)
        ->once()
        ->andReturn(new \Illuminate\Pagination\LengthAwarePaginator([], 0, 100));

    $service = makeService($repository);
    $service->listBooks([], 'title', 999);
});
