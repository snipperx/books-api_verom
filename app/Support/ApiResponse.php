<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\JsonResponse;

final class ApiResponse
{
    public static function success(
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

    public static function error(
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
