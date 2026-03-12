<?php
// app/Swagger/Responses/NotFoundResponse.php

declare(strict_types=1);

namespace App\Swagger\Responses;

/**
 * @OA\Response(
 *     response="NotFound",
 *     description="The requested resource does not exist or has been soft-deleted. Soft-deleted books return 404 to avoid leaking information about previously existing records.",
 *     @OA\Header(
 *         header="X-Request-Id",
 *         description="Unique identifier for this request",
 *         @OA\Schema(type="string", format="uuid")
 *     ),
 *     @OA\JsonContent(
 *         required={"status","code","message"},
 *         @OA\Property(property="status",  type="string", enum={"error"}, example="error"),
 *         @OA\Property(
 *             property="code",
 *             type="string",
 *             enum={"NOT_FOUND","BOOK_NOT_FOUND"},
 *             example="BOOK_NOT_FOUND"
 *         ),
 *         @OA\Property(
 *             property="message",
 *             type="string",
 *             example="Book with ID [42] was not found."
 *         )
 *     )
 * )
 */
final class NotFoundResponse implements SwaggerResponseDefinition {}
