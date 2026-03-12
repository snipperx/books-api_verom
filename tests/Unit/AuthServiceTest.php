<?php

declare(strict_types=1);

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\TokenLimitExceededException;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ─── Login ────────────────────────────────────────────────────────────────────

it('throws InvalidCredentialsException for a wrong password', function (): void {
    User::factory()->create(['email' => 'test@example.com']);

    $service = app(AuthService::class);

    expect(fn () => $service->login('test@example.com', 'wrong-password', 'test'))
        ->toThrow(InvalidCredentialsException::class);
});

it('throws InvalidCredentialsException for an unknown email', function (): void {
    $service = app(AuthService::class);

    expect(fn () => $service->login('nobody@example.com', 'Password1!', 'test'))
        ->toThrow(InvalidCredentialsException::class);
});

it('throws TokenLimitExceededException when limit is reached', function (): void {
    $user  = User::factory()->create();
    $limit = (int) config('sanctum.max_tokens_per_user', 10);

    foreach (range(1, $limit) as $i) {
        $user->createToken("device-{$i}");
    }

    $service = app(AuthService::class);

    expect(fn () => $service->login($user->email, 'Password1!', 'overflow-device'))
        ->toThrow(TokenLimitExceededException::class);
});

// ─── Register ────────────────────────────────────────────────────────────────

it('creates a user and returns a token on registration', function (): void {
    $service = app(AuthService::class);

    $token = $service->register([
        'name'     => 'Test User',
        'email'    => 'new@example.com',
        'password' => 'Password1!',
    ], 'test-device');

    expect($token->plainTextToken)->not->toBeEmpty();
    $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
});

// ─── Logout ───────────────────────────────────────────────────────────────────

it('revokes only the current token on logout', function (): void {
    $user = User::factory()->create();
    $user->createToken('device-1');
    $user->createToken('device-2');

    // Simulate an active token
    $activeToken = $user->createToken('active-device');
    $tokenId     = $activeToken->accessToken->id;

    // Act as the user with that specific token
    $this->actingAs($user, 'sanctum');
    $user->withAccessToken($activeToken->accessToken);

    $service = app(AuthService::class);
    $service->logout($user);

    $this->assertDatabaseMissing('personal_access_tokens', ['id' => $tokenId]);
    // Other tokens survive
    $this->assertDatabaseCount('personal_access_tokens', 2);
});
