<?php
// app/Swagger/Responses/ValidationErrorResponse.php

declare(strict_types=1);

namespace App\Swagger\Responses;

/**
 * @OA\Response(
 *     response="ValidationError",
 *     description="The submitted data failed validation or a business rule was violated.",
 *     @OA\Header(
 *         header="X-Request-Id",
 *         description="Unique identifier for this request",
 *         @OA\Schema(type="string", format="uuid")
 *     ),
 *     @OA\JsonContent(
 *         required={"status","code","message"},
 *         @OA\Property(property="status", type="string", enum={"error"}, example="error"),
 *         @OA\Property(
 *             property="code",
 *             type="string",
 *             enum={"VALIDATION_FAILED","BOOK_NOT_AVAILABLE","TOKEN_LIMIT_EXCEEDED"},
 *             example="VALIDATION_FAILED"
 *         ),
 *         @OA\Property(
 *             property="message",
 *             type="string",
 *             example="The given data was invalid."
 *         ),
 *         @OA\Property(
 *             property="errors",
 *             type="object",
 *             nullable=true,
 *             description="Field-level messages keyed by field name. Present only when code is VALIDATION_FAILED.",
 *             @OA\AdditionalProperties(
 *                 type="array",
 *                 @OA\Items(type="string")
 *             )
 *         )
 *     )
 * )
 */
final class ValidationErrorResponse implements SwaggerResponseDefinition {}
