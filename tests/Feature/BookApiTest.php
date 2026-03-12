<?php

declare(strict_types=1);

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

// ─── Helpers ────────────────────────────────────────────────────────────────

function actingAsUser(): User
{
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    return $user;
}

function validBookPayload(array $overrides = []): array
{
    return array_merge([
        'title'            => 'The Clean Coder',
        'author'           => 'Robert C. Martin',
        'isbn'             => '9780137081073',
        'published_at'     => '2011-05-13',
        'genre'            => 'non-fiction',
        'description'      => 'A book about professional software development.',
        'available_copies' => 5,
    ], $overrides);
}

// ─── GET /api/v1/books ───────────────────────────────────────────────────────

it('returns a paginated list of books', function (): void {
    Book::factory()->count(20)->create();

    $response = $this->getJson('/api/v1/books');

    $response->assertOk()
        ->assertJsonStructure([
            'status',
            'data' => [['id', 'title', 'author', 'isbn', 'genre', 'available_copies']],
            'meta' => ['current_page', 'per_page', 'total', 'last_page'],
        ])
        ->assertJsonPath('status', 'success');
});

it('filters books by genre', function (): void {
    Book::factory()->count(3)->create(['genre' => 'fiction']);
    Book::factory()->count(5)->create(['genre' => 'science']);

    $response = $this->getJson('/api/v1/books?filter[genre]=fiction');

    $response->assertOk();
    $data = $response->json('data');
    expect($data)->each(fn ($book) => $book->genre->toBe('fiction'));
});

it('filters books by available copies', function (): void {
    Book::factory()->count(3)->create(['available_copies' => 0]);
    Book::factory()->count(2)->create(['available_copies' => 5]);

    $response = $this->getJson('/api/v1/books?filter[available]=true');

    $response->assertOk();
    $data = $response->json('data');
    expect($data)->toHaveCount(2);
});

it('sorts books by title ascending', function (): void {
    Book::factory()->create(['title' => 'Zebra Book']);
    Book::factory()->create(['title' => 'Apple Book']);

    $response = $this->getJson('/api/v1/books?sort=title');

    $response->assertOk();
    $titles = collect($response->json('data'))->pluck('title');
    expect($titles->first())->toBe('Apple Book');
});

it('respects per_page limit capped at 100', function (): void {
    Book::factory()->count(10)->create();

    $response = $this->getJson('/api/v1/books?per_page=200');

    $response->assertOk()
        ->assertJsonPath('meta.per_page', 100);
});

// ─── GET /api/v1/books/{id} ──────────────────────────────────────────────────

it('returns a single book by id', function (): void {
    $book = Book::factory()->create();

    $this->getJson("/api/v1/books/{$book->id}")
        ->assertOk()
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('data.id', $book->id);
});

it('returns 404 for a non-existent book', function (): void {
    $this->getJson('/api/v1/books/999999')
        ->assertNotFound()
        ->assertJsonPath('code', 'NOT_FOUND');
});

// ─── POST /api/v1/books ──────────────────────────────────────────────────────

it('creates a book when authenticated', function (): void {
    actingAsUser();

    $this->postJson('/api/v1/books', validBookPayload())
        ->assertCreated()
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('data.isbn', '9780137081073');
});

it('returns 401 when creating a book without authentication', function (): void {
    $this->postJson('/api/v1/books', validBookPayload())
        ->assertUnauthorized();
});

it('validates required fields on create', function (): void {
    actingAsUser();

    $this->postJson('/api/v1/books', [])
        ->assertUnprocessable()
        ->assertJsonPath('code', 'VALIDATION_FAILED')
        ->assertJsonStructure(['errors' => ['title', 'author', 'isbn']]);
});

it('rejects a future published_at date', function (): void {
    actingAsUser();

    $this->postJson('/api/v1/books', validBookPayload(['published_at' => now()->addYear()->toDateString()]))
        ->assertUnprocessable()
        ->assertJsonPath('code', 'VALIDATION_FAILED');
});

it('rejects an invalid isbn', function (): void {
    actingAsUser();

    $this->postJson('/api/v1/books', validBookPayload(['isbn' => '0000000000000']))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['isbn']);
});

it('rejects a duplicate isbn', function (): void {
    actingAsUser();
    Book::factory()->create(['isbn' => '9780137081073']);

    $this->postJson('/api/v1/books', validBookPayload())
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['isbn']);
});

// ─── PUT /api/v1/books/{id} ──────────────────────────────────────────────────

it('fully updates a book', function (): void {
    actingAsUser();
    $book = Book::factory()->create();

    $payload = validBookPayload(['title' => 'Updated Title', 'isbn' => '9780132350884']);

    $this->putJson("/api/v1/books/{$book->id}", $payload)
        ->assertOk()
        ->assertJsonPath('data.title', 'Updated Title');
});

// ─── PATCH /api/v1/books/{id} ────────────────────────────────────────────────

it('partially updates a book', function (): void {
    actingAsUser();
    $book = Book::factory()->create(['available_copies' => 3]);

    $this->patchJson("/api/v1/books/{$book->id}", ['available_copies' => 10])
        ->assertOk()
        ->assertJsonPath('data.available_copies', 10);
});

// ─── DELETE /api/v1/books/{id} ───────────────────────────────────────────────

it('soft-deletes a book', function (): void {
    actingAsUser();
    $book = Book::factory()->create();

    $this->deleteJson("/api/v1/books/{$book->id}")
        ->assertOk()
        ->assertJsonPath('status', 'success');

    $this->assertSoftDeleted('books', ['id' => $book->id]);
});

// ─── GET /api/v1/books/search ────────────────────────────────────────────────

it('searches books by title', function (): void {
    Book::factory()->create(['title' => 'Laravel Design Patterns']);
    Book::factory()->create(['title' => 'PHP Best Practices']);

    $this->getJson('/api/v1/books/search?q=Laravel')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

it('requires a search query of at least 2 characters', function (): void {
    $this->getJson('/api/v1/books/search?q=a')
        ->assertUnprocessable();
});

// ─── POST /api/v1/books/{id}/borrow ─────────────────────────────────────────

it('borrows a book and decrements available copies', function (): void {
    actingAsUser();
    $book = Book::factory()->create(['available_copies' => 3]);

    $this->postJson("/api/v1/books/{$book->id}/borrow")
        ->assertOk()
        ->assertJsonPath('data.available_copies', 2);
});

it('returns 422 when borrowing an unavailable book', function (): void {
    actingAsUser();
    $book = Book::factory()->create(['available_copies' => 0]);

    $this->postJson("/api/v1/books/{$book->id}/borrow")
        ->assertUnprocessable()
        ->assertJsonPath('code', 'BOOK_NOT_AVAILABLE');
});

// ─── POST /api/v1/books/{id}/return ─────────────────────────────────────────

it('returns a book and increments available copies', function (): void {
    actingAsUser();
    $book = Book::factory()->create(['available_copies' => 2]);

    $this->postJson("/api/v1/books/{$book->id}/return")
        ->assertOk()
        ->assertJsonPath('data.available_copies', 3);
});
