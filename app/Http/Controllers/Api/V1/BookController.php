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

    /**
     * @OA\Get(
     *     path="/api/v1/books",
     *     tags={"Books"},
     *     summary="List books",
     *     description="Returns paginated books with optional filters.",
     *     @OA\Parameter(
     *         name="filter[genre]",
     *         in="query",
     *         @OA\Schema(type="string", example="fiction")
     *     ),
     *     @OA\Parameter(
     *         name="filter[author]",
     *         in="query",
     *         @OA\Schema(type="string", example="Tolkien")
     *     ),
     *     @OA\Parameter(
     *         name="filter[available]",
     *         in="query",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         @OA\Schema(type="string", example="-created_at")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(response=200, description="List of books")
     * )
     */
    public function index(Request $request): BookCollection
    {
        $filters = [
            'genre' => $request->input('filter.genre'),
            'author' => $request->input('filter.author'),
            'available' => filter_var(
                $request->input('filter.available'),
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            ),
        ];

        $books = $this->bookService->listBooks(
            filters: array_filter($filters, fn ($v) => $v !== null),
            sort: $request->input('sort', 'created_at'),
            perPage: (int) $request->input('per_page', 15),
        );

        return new BookCollection($books);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/books/{book}",
     *     tags={"Books"},
     *     summary="Get a book",
     *     @OA\Parameter(
     *         name="book",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Book details"),
     *     @OA\Response(response=404, description="Book not found")
     * )
     */
    public function show(Book $book): JsonResponse
    {
        return ApiResponse::success(data: new BookResource($book));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/books",
     *     tags={"Books"},
     *     summary="Create a book",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreBookRequest")
     *     ),
     *     @OA\Response(response=201, description="Book created"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = $this->bookService->createBook($request->validated());

        return ApiResponse::success(
            data: new BookResource($book),
            message: 'Book created successfully.',
            statusCode: 201,
        );
    }

    /**
     * @OA\Put(
     *     path="/api/v1/books/{book}",
     *     tags={"Books"},
     *     summary="Update book",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="book",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateBookRequest")
     *     ),
     *     @OA\Response(response=200, description="Book updated")
     * )
     */
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $updated = $this->bookService->updateBook($book, $request->validated());

        return ApiResponse::success(
            data: new BookResource($updated),
            message: 'Book updated successfully.',
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/books/{book}",
     *     tags={"Books"},
     *     summary="Partially update book",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PatchBookRequest")
     *     ),
     *     @OA\Response(response=200, description="Book updated")
     * )
     */
    public function patch(PatchBookRequest $request, Book $book): JsonResponse
    {
        $updated = $this->bookService->updateBook($book, $request->validated());

        return ApiResponse::success(
            data: new BookResource($updated),
            message: 'Book updated successfully.',
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/books/{book}",
     *     tags={"Books"},
     *     summary="Delete book",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Book deleted")
     * )
     */
    public function destroy(Book $book): JsonResponse
    {
        $this->bookService->deleteBook($book);

        return ApiResponse::success(message: 'Book deleted successfully.');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/books/search",
     *     tags={"Books"},
     *     summary="Search books",
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", example="tolkien")
     *     ),
     *     @OA\Response(response=200, description="Search results")
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => ['required', 'string', 'min:2', 'max:100']]);

        $books = $this->bookService->searchBooks($request->input('q'));

        return ApiResponse::success(
            data: BookResource::collection($books),
            meta: ['total' => $books->count()],
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/books/{book}/borrow",
     *     tags={"Books"},
     *     summary="Borrow a book",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Book borrowed")
     * )
     */
    public function borrow(Book $book): JsonResponse
    {
        $updated = $this->bookService->borrowBook($book, $this->resolveAuthenticatedUserId());

        return ApiResponse::success(
            data: new BookResource($updated),
            message: 'Book borrowed successfully.',
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/books/{book}/return",
     *     tags={"Books"},
     *     summary="Return a book",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Book returned")
     * )
     */
    public function returnBook(Book $book): JsonResponse
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
