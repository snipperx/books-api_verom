<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Contracts\BookServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\PatchBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class BookController extends Controller
{
    public function __construct(
        private readonly BookServiceInterface $bookService,
    ) {}

    public function index(Request $request): BookCollection
    {
        $filters = [
            'genre'     => $request->input('filter.genre'),
            'author'    => $request->input('filter.author'),
            'available' => filter_var($request->input('filter.available'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        ];

        $books = $this->bookService->listBooks(
            filters: array_filter($filters, fn ($value): bool => $value !== null),
            sort: $request->input('sort', 'created_at'),
            perPage: (int) $request->input('per_page', 15),
        );

        return new BookCollection($books);
    }

    public function show(Book $book): JsonResponse
    {
        return ApiResponse::success(data: new BookResource($book));
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = $this->bookService->createBook($request->validated());

        return ApiResponse::success(
            data: new BookResource($book),
            message: 'Book created successfully.',
            statusCode: 201,
        );
    }

    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $updated = $this->bookService->updateBook($book, $request->validated());

        return ApiResponse::success(
            data: new BookResource($updated),
            message: 'Book updated successfully.',
        );
    }

    public function patch(PatchBookRequest $request, Book $book): JsonResponse
    {
        $updated = $this->bookService->updateBook($book, $request->validated());

        return ApiResponse::success(
            data: new BookResource($updated),
            message: 'Book updated successfully.',
        );
    }

    public function destroy(Book $book): JsonResponse
    {
        $this->bookService->deleteBook($book);

        return ApiResponse::success(message: 'Book deleted successfully.');
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => ['required', 'string', 'min:2', 'max:100']]);

        $books = $this->bookService->searchBooks($request->input('q'));

        return ApiResponse::success(
            data: BookResource::collection($books),
            meta: ['total' => $books->count()],
        );
    }

    public function borrow(Book $book): JsonResponse
    {
        $updated = $this->bookService->borrowBook($book, $this->resolveAuthenticatedUserId());

        return ApiResponse::success(
            data: new BookResource($updated),
            message: 'Book borrowed successfully.',
        );
    }

    public function return(Book $book): JsonResponse
    {
        $updated = $this->bookService->returnBook($book, $this->resolveAuthenticatedUserId());

        return ApiResponse::success(
            data: new BookResource($updated),
            message: 'Book returned successfully.',
        );
    }

    private function resolveAuthenticatedUserId(): int
    {
        return (int) auth()->id();
    }
}
