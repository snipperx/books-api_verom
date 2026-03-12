<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\TokenLimitExceededException;
use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

interface AuthServiceInterface
{
    /**
     * Register a new user and return their first access token.
     *
     * @param array{name: string, email: string, password: string} $attributes
     */
    public function register(array $attributes, string $deviceName): NewAccessToken;

    /**
     * Authenticate a user by credentials and issue a new token.
     *
     * @throws InvalidCredentialsException
     * @throws TokenLimitExceededException
     */
    public function login(string $email, string $password, string $deviceName): NewAccessToken;

    /**
     * Revoke the token used to make the current request.
     */
    public function logout(User $user): void;

    /**
     * Revoke all tokens belonging to the user.
     */
    public function logoutAll(User $user): void;

    /**
     * Return the currently authenticated user.
     */
    public function currentUser(User $user): User;
}
