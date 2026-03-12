<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Contracts\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthTokenResource;
use App\Http\Resources\UserResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService,
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $token = $this->authService->register(
                attributes: $request->only(['name', 'email', 'password']),
                deviceName: $request->validated('device_name'),
            );

            return ApiResponse::success(
                data: $this->buildTokenPayload($token),
                message: 'Account created successfully.',
                statusCode: 201,
            );

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->login(
            email: $request->validated('email'),
            password: $request->validated('password'),
            deviceName: $request->validated('device_name'),
        );

        return ApiResponse::success(
            data: $this->buildTokenPayload($token),
            message: 'Authenticated successfully.',
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return ApiResponse::success(message: 'Token revoked. You have been logged out.');
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $this->authService->logoutAll($request->user());

        return ApiResponse::success(message: 'All tokens revoked. You have been logged out of all devices.');
    }

    public function me(Request $request): JsonResponse
    {
        $user = $this->authService->currentUser($request->user());

        return ApiResponse::success(data: new UserResource($user));
    }

    /**
     * @return array{user: UserResource, token: AuthTokenResource}
     */
    private function buildTokenPayload(mixed $token): array
    {
        return [
            'user'  => new UserResource($token->accessToken->tokenable),
            'token' => new AuthTokenResource($token),
        ];
    }
}
