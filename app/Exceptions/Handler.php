<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

final class Handler extends ExceptionHandler
{
    /**
     * @var list<class-string<Throwable>>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (BookNotFoundException $e) {
            return $this->domainErrorResponse($e->getMessage(), $e->getErrorCode(), $e->getStatusCode());
        });

        $this->renderable(function (BookNotAvailableException $e) {
            return $this->domainErrorResponse($e->getMessage(), $e->getErrorCode(), $e->getStatusCode());
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return $this->domainErrorResponse('The requested resource was not found.', 'NOT_FOUND', 404);
        });

        $this->renderable(function (AuthenticationException $e) {
            return $this->domainErrorResponse('Unauthenticated. Please provide a valid Bearer token.', 'UNAUTHENTICATED', 401);
        });

        $this->renderable(function (ValidationException $e): JsonResponse {
            return response()->json([
                'status'  => 'error',
                'code'    => 'VALIDATION_FAILED',
                'message' => 'The given data was invalid.',
                'errors'  => $e->errors(),
            ], 422);
        });
    }

    private function domainErrorResponse(string $message, string $code, int $status): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'code'    => $code,
            'message' => $message,
        ], $status);
    }
}
