<?php

declare(strict_types=1);

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="AuthToken",
 *     title="Auth Token",
 *     description="A newly issued Sanctum Bearer token.
 * **Important:** the `access_token` value is only returned once.
 * Store it securely — it cannot be retrieved again.",
 *     required={"access_token","token_type","abilities"},
 *
 *     @OA\Property(property="access_token", type="string",
 *                  example="1|laravel_sanctum_abc123...",
 *                  description="The plaintext Bearer token — shown once only"),
 *     @OA\Property(property="token_type",   type="string", example="Bearer"),
 *     @OA\Property(property="abilities",    type="array",
 *                  @OA\Items(type="string", example="books:read"),
 *                  description="List of scopes granted to this token"),
 *     @OA\Property(property="expires_at",   type="string", format="date-time",
 *                  nullable=true, example="2024-12-31T23:59:59+00:00",
 *                  description="null if token never expires"),
 *     @OA\Property(property="note",         type="string",
 *                  example="Store this token securely. It will not be shown again.")
 * )
 *
 * @OA\Schema(
 *     schema="AuthResponse",
 *     title="Auth Response",
 *     description="Response envelope returned after successful register or login",
 *
 *     @OA\Property(property="status", type="string", example="success"),
 *     @OA\Property(property="message", type="string", example="Authenticated successfully."),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(property="user",  ref="#/components/schemas/User"),
 *         @OA\Property(property="token", ref="#/components/schemas/AuthToken")
 *     )
 * )
 */
final class AuthTokenSchema implements SwaggerSchemaDefinition {}
