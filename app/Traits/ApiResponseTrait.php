<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected function successResponse(
        mixed $data = null,
        ?string $message = null,
        int $statusCode = 200,
        array $meta = [],
    ): JsonResponse {
        $payload = ['status' => 'success'];

        if ($message !== null) {
            $payload['message'] = $message;
        }

        if ($data !== null) {
            $payload['data'] = $data;
        }

        if (!empty($meta)) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $statusCode);
    }

    protected function errorResponse(
        string $message,
        int $statusCode = 400,
        array $errors = [],
    ): JsonResponse {
        $payload = [
            'status'  => 'error',
            'message' => $message,
        ];

        if (!empty($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $statusCode);
    }
}
