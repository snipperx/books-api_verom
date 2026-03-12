<?php

declare(strict_types=1);

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="An authenticated library user",
 *     required={"id","name","email"},
 *
 *     @OA\Property(property="id",            type="integer", example=1),
 *     @OA\Property(property="name",          type="string",  example="Ada Lovelace"),
 *     @OA\Property(property="email",         type="string",  format="email",
 *                  example="ada@example.com"),
 *     @OA\Property(property="last_login_at", type="string",  format="date-time",
 *                  nullable=true, example="2024-06-01T08:00:00+00:00"),
 *     @OA\Property(property="created_at",    type="string",  format="date-time",
 *                  example="2024-01-01T00:00:00+00:00")
 * )
 *
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     title="Register Request",
 *     required={"name","email","password","password_confirmation","device_name"},
 *
 *     @OA\Property(property="name",                  type="string", example="Ada Lovelace"),
 *     @OA\Property(property="email",                 type="string", format="email",
 *                  example="ada@example.com"),
 *     @OA\Property(property="password",              type="string", format="password",
 *                  minLength=12, example="Password1!",
 *                  description="Min 12 chars. Must contain uppercase, lowercase, digit, and special character."),
 *     @OA\Property(property="password_confirmation", type="string", format="password",
 *                  example="Password1!"),
 *     @OA\Property(property="device_name",           type="string",
 *                  example="MacBook Pro — Chrome",
 *                  description="A human-readable label to identify this token")
 * )
 *
 * @OA\Schema(
 *     schema="LoginRequest",
 *     title="Login Request",
 *     required={"email","password","device_name"},
 *
 *     @OA\Property(property="email",       type="string", format="email",
 *                  example="ada@example.com"),
 *     @OA\Property(property="password",    type="string", format="password",
 *                  example="Password1!"),
 *     @OA\Property(property="device_name", type="string", example="MacBook Pro — Chrome")
 * )
 */
final class UserSchema implements SwaggerSchemaDefinition {}
