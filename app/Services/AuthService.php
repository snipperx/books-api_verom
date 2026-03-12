<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AuthServiceInterface;
use App\Enums\TokenAbility;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\TokenLimitExceededException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;

final class AuthService implements AuthServiceInterface
{
    public function register(array $attributes, string $deviceName): NewAccessToken
    {
        $user = User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => Hash::make($attributes['password']),
        ]);

        return $this->issueToken($user, $deviceName, TokenAbility::allAbilities());
    }

    public function login(string $email, string $password, string $deviceName): NewAccessToken
    {
        $user = User::where('email', $email)->first();

        if ($user === null || ! Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        if ($user->hasExceededTokenLimit()) {
            throw new TokenLimitExceededException(
                (int) config('sanctum.max_tokens_per_user', 10)
            );
        }

        $user->updateLastLogin();

        return $this->issueToken($user, $deviceName, TokenAbility::allAbilities());
    }

    public function logout(User $user): void
    {
        $user->revokeCurrentToken();
    }

    public function logoutAll(User $user): void
    {
        $user->revokeAllTokens();
    }

    public function currentUser(User $user): User
    {
        return $user->fresh();
    }

    private function issueToken(User $user, string $deviceName, array $abilities): NewAccessToken
    {
        return $user->createToken(
            name: $deviceName,
            abilities: $abilities,
            expiresAt: $this->resolveTokenExpiry(),
        );
    }

    private function resolveTokenExpiry(): ?\DateTimeInterface
    {
        $minutes = config('sanctum.expiration');

        return $minutes ? now()->addMinutes((int) $minutes) : null;
    }
}
