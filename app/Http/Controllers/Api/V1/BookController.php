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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class BookController extends Controller
{
    public function __construct(
        private readonly BookServiceInterface $bookService,
    ) {}

    /**
     * @param Request $request
     * @return BookCollection
     */
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

    /**
     * @param Book $book
     * @return JsonResponse
     */
    public function show(Book $book): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => new BookResource($book),
        ]);
    }

    /**
     * @param StoreBookRequest $request
     * @return JsonResponse
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = $this->bookService->createBook($request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Book created successfully.',
            'data'    => new BookResource($book),
        ], 201);
    }

    /**
     * @param UpdateBookRequest $request
     * @param Book $book
     * @return JsonResponse
     */
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $updated = $this->bookService->updateBook($book, $request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Book updated successfully.',
            'data'    => new BookResource($updated),
        ]);
    }

    /**
     * @param PatchBookRequest $request
     * @param Book $book
     * @return JsonResponse
     */
    public function patch(PatchBookRequest $request, Book $book): JsonResponse
    {
        $updated = $this->bookService->updateBook($book, $request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Book updated successfully.',
            'data'    => new BookResource($updated),
        ]);
    }

    /**
     * @param Book $book
     * @return JsonResponse
     */
    public function destroy(Book $book): JsonResponse
    {
        $this->bookService->deleteBook($book);

        return response()->json([
            'status'  => 'success',
            'message' => 'Book deleted successfully.',
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => ['required', 'string', 'min:2', 'max:100']]);

        $books = $this->bookService->searchBooks($request->input('q'));

        return response()->json([
            'status' => 'success',
            'data'   => BookResource::collection($books),
            'meta'   => ['total' => $books->count()],
        ]);
    }

    /**
     * @param Book $book
     * @return JsonResponse
     */
    public function borrow(Book $book): JsonResponse
    {
        $updated = $this->bookService->borrowBook($book, $this->resolveAuthenticatedUserId());

        return response()->json([
            'status'  => 'success',
            'message' => 'Book borrowed successfully.',
            'data'    => new BookResource($updated),
        ]);
    }

    /**
     * @param Book $book
     * @return JsonResponse
     */
    public function return(Book $book): JsonResponse
    {
        $updated = $this->bookService->returnBook($book, $this->resolveAuthenticatedUserId());

        return response()->json([
            'status'  => 'success',
            'message' => 'Book returned successfully.',
            'data'    => new BookResource($updated),
        ]);
    }

    /**
     * @return int
     */
    private function resolveAuthenticatedUserId(): int
    {
        return (int) auth()->id();
    }
}
