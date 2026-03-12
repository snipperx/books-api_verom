<?php
// app/Swagger/Responses/ForbiddenResponse.php

declare(strict_types=1);

namespace App\Swagger\Responses;

/**
 * @OA\Response(
 *     response="Forbidden",
 *     description="Authenticated but token lacks the required ability. 401 means unknown identity — 403 means known identity with insufficient scope. Resolution: re-authenticate without read_only:true to obtain a full-ability token.",
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
 *             enum={"FORBIDDEN","INSUFFICIENT_ABILITIES"},
 *             example="INSUFFICIENT_ABILITIES"
 *         ),
 *         @OA\Property(
 *             property="message",
 *             type="string",
 *             example="Your token does not have the required ability: books:write."
 *         ),
 *         @OA\Property(
 *             property="required_ability",
 *             type="string",
 *             nullable=true,
 *             enum={"books:read","books:write","books:borrow","tokens:manage"},
 *             example="books:write"
 *         ),
 *         @OA\Property(
 *             property="token_abilities",
 *             type="array",
 *             nullable=true,
 *             @OA\Items(
 *                 type="string",
 *                 enum={"books:read","books:write","books:borrow","tokens:manage"}
 *             )
 *         )
 *     )
 * )
 */
final class ForbiddenResponse implements SwaggerResponseDefinition {}
