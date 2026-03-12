<?php
// app/Swagger/Responses/TooManyRequestsResponse.php

declare(strict_types=1);

namespace App\Swagger\Responses;

/**
 * @OA\Response(
 *     response="TooManyRequests",
 *     description="Rate limit exceeded. Limits: read=60/min, write=20/min, auth=10/min per IP. Wait for Retry-After seconds before retrying.",
 *     @OA\Header(
 *         header="Retry-After",
 *         description="Seconds to wait before retrying",
 *         @OA\Schema(type="integer", example=30)
 *     ),
 *     @OA\Header(
 *         header="X-RateLimit-Limit",
 *         description="Maximum requests allowed in this window",
 *         @OA\Schema(type="integer", example=60)
 *     ),
 *     @OA\Header(
 *         header="X-RateLimit-Remaining",
 *         description="Requests remaining in this window",
 *         @OA\Schema(type="integer", example=0)
 *     ),
 *     @OA\JsonContent(
 *         required={"status","code","message"},
 *         @OA\Property(property="status",      type="string",  enum={"error"}, example="error"),
 *         @OA\Property(property="code",        type="string",  enum={"TOO_MANY_REQUESTS"}, example="TOO_MANY_REQUESTS"),
 *         @OA\Property(property="message",     type="string",  example="Too many requests. Please slow down."),
 *         @OA\Property(property="retry_after", type="integer", example=30)
 *     )
 * )
 */
final class TooManyRequestsResponse implements SwaggerResponseDefinition {}
