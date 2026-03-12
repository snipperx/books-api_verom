<?php
// app/Swagger/Responses/UnauthorizedResponse.php

declare(strict_types=1);

namespace App\Swagger\Responses;

/**
 * @OA\Response(
 *     response="Unauthorized",
 *     description="Authentication required or credentials are invalid. Common causes: missing Authorization header, revoked token, expired token, wrong credentials.",
 *     @OA\Header(
 *         header="WWW-Authenticate",
 *         description="Indicates Bearer authentication is required",
 *         @OA\Schema(type="string", example="Bearer")
 *     ),
 *     @OA\JsonContent(
 *         required={"status","code","message"},
 *         @OA\Property(property="status",  type="string", enum={"error"}, example="error"),
 *         @OA\Property(
 *             property="code",
 *             type="string",
 *             enum={"UNAUTHENTICATED","INVALID_CREDENTIALS"},
 *             example="UNAUTHENTICATED"
 *         ),
 *         @OA\Property(
 *             property="message",
 *             type="string",
 *             example="Unauthenticated. Please provide a valid Bearer token."
 *         )
 *     )
 * )
 */
final class UnauthorizedResponse implements SwaggerResponseDefinition {}
