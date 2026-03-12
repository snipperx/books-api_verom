<?php
// app/Swagger/Schemas/ErrorResponseSchema.php

declare(strict_types=1);

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     title="Error Response",
 *     required={"status","code","message"},
 *     @OA\Property(property="status",  type="string", enum={"error"}, example="error"),
 *     @OA\Property(property="code",    type="string", example="NOT_FOUND"),
 *     @OA\Property(property="message", type="string", example="The requested resource was not found.")
 * )
 *
 * @OA\Schema(
 *     schema="ValidationErrorResponse",
 *     title="Validation Error Response",
 *     allOf={@OA\Schema(ref="#/components/schemas/ErrorResponse")},
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         nullable=true,
 *         @OA\AdditionalProperties(
 *             type="array",
 *             @OA\Items(type="string")
 *         )
 *     )
 * )
 */
final class ErrorResponseSchema implements SwaggerSchemaDefinition {}
