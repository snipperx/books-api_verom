<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(Tests\TestCase::class, RefreshDatabase::class);

// ─── Helpers ─────────────────────────────────────────────────────────────────

function registerPayload(array $overrides = []): array
{
    return array_merge([
        'name'                  => 'Ada Lovelace',
        'email'                 => 'ada@example.com',
        'password'              => 'Password1!',
        'password_confirmation' => 'Password1!',
        'device_name'           => 'phpunit-test-runner',
    ], $overrides);
}

// ─── POST /api/v1/auth/register ──────────────────────────────────────────────

it('registers a new user and returns a Bearer token', function (): void {
    $response = $this->postJson('/api/v1/auth/register', registerPayload());

    $response
        ->assertCreated()
        ->assertJsonPath('status', 'success')
        ->assertJsonStructure([
            'data' => [
                'user'  => ['id', 'name', 'email'],
                'token' => ['access_token', 'token_type', 'expires_at', 'abilities'],
            ],
        ]);

    $this->assertDatabaseHas('users', ['email' => 'ada@example.com']);
});

it('rejects registration with a weak password', function (): void {
    $this->postJson('/api/v1/auth/register', registerPayload([
        'password'              => 'weakpassword',
        'password_confirmation' => 'weakpassword',
    ]))->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});

it('rejects registration when email is already taken', function (): void {
    User::factory()->create(['email' => 'ada@example.com']);

    $this->postJson('/api/v1/auth/register', registerPayload())
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('rejects registration when password confirmation does not match', function (): void {
    $this->postJson('/api/v1/auth/register', registerPayload([
        'password_confirmation' => 'DifferentPassword1!',
    ]))->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});

it('requires device_name on registration', function (): void {
    $this->postJson('/api/v1/auth/register', registerPayload(['device_name' => '']))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['device_name']);
});

// ─── POST /api/v1/auth/login ─────────────────────────────────────────────────

it('issues a token with valid credentials', function (): void {
    User::factory()->create(['email' => 'ada@example.com']);

    $this->postJson('/api/v1/auth/login', [
        'email'       => 'ada@example.com',
        'password'    => 'Password1!',
        'device_name' => 'test-device',
    ])->assertOk()
        ->assertJsonPath('status', 'success')
        ->assertJsonStructure([
            'data' => ['user', 'token' => ['access_token', 'token_type']],
        ]);
});

it('returns 401 for incorrect password', function (): void {
    User::factory()->create(['email' => 'ada@example.com']);

    $this->postJson('/api/v1/auth/login', [
        'email'       => 'ada@example.com',
        'password'    => 'WrongPassword1!',
        'device_name' => 'test-device',
    ])->assertUnauthorized()
        ->assertJsonPath('code', 'INVALID_CREDENTIALS');
});

it('returns 401 for non-existent email', function (): void {
    $this->postJson('/api/v1/auth/login', [
        'email'       => 'ghost@example.com',
        'password'    => 'Password1!',
        'device_name' => 'test-device',
    ])->assertUnauthorized()
        ->assertJsonPath('code', 'INVALID_CREDENTIALS');
});

it('rejects login when token limit is exceeded', function (): void {
    $user  = User::factory()->create();
    $limit = (int) config('sanctum.max_tokens_per_user', 10);

    foreach (range(1, $limit) as $i) {
        $user->createToken("device-{$i}");
    }

    $this->postJson('/api/v1/auth/login', [
        'email'       => $user->email,
        'password'    => 'Password1!',
        'device_name' => 'one-too-many',
    ])->assertUnprocessable()
        ->assertJsonPath('code', 'TOKEN_LIMIT_EXCEEDED');
});

// ─── GET /api/v1/auth/me ─────────────────────────────────────────────────────

it('returns the authenticated user profile', function (): void {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $this->getJson('/api/v1/auth/me')
        ->assertOk()
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('data.email', $user->email);
});

it('returns 401 for unauthenticated /me request', function (): void {
    $this->getJson('/api/v1/auth/me')
        ->assertUnauthorized();
});

// ─── POST /api/v1/auth/logout ────────────────────────────────────────────────

it('revokes the current token on logout', function (): void {
    $user  = User::factory()->create();
    $token = $user->createToken('test-device');

    $this->withToken($token->plainTextToken)
        ->postJson('/api/v1/auth/logout')
        ->assertOk()
        ->assertJsonPath('status', 'success');

    $this->assertDatabaseCount('personal_access_tokens', 0);
});

// ─── POST /api/v1/auth/logout-all ────────────────────────────────────────────

it('revokes all tokens on logout-all', function (): void {
    $user = User::factory()->create();

    foreach (range(1, 3) as $i) {
        $user->createToken("device-{$i}");
    }

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/auth/logout-all')
        ->assertOk()
        ->assertJsonPath('message', 'All tokens revoked. You have been logged out of all devices.');

    $this->assertDatabaseCount('personal_access_tokens', 0);
});

// ─── Protected book endpoints respect auth ───────────────────────────────────

it('rejects POST /books without a token', function (): void {
    $this->postJson('/api/v1/books', [])
        ->assertUnauthorized()
        ->assertJsonPath('code', 'UNAUTHENTICATED');
});

it('allows POST /books with a valid token', function (): void {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $this->postJson('/api/v1/books', [
        'title'            => 'Clean Code',
        'author'           => 'Robert C. Martin',
        'isbn'             => '9780132350884',
        'published_at'     => '2008-08-01',
        'genre'            => 'non-fiction',
        'available_copies' => 3,
    ])->assertCreated();
});
