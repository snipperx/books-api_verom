<?php
// app/Swagger/Schemas/PaginationMetaSchema.php

declare(strict_types=1);

namespace App\Swagger\Schemas;

/**
 * Pagination metadata schema.
 *
 * This file defines only the reusable PaginationMeta component.
 *
 * BookListResponse and BookSearchResponse were previously defined here
 * but have been moved to SuccessResponseSchema.php where all success
 * envelope shapes are centralised.
 *
 * @OA\Schema(
 *     schema="PaginationMeta",
 *     title="Pagination Meta",
 *     description="Metadata attached to every paginated list response.",
 *     required={"current_page","per_page","total","last_page"},
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="per_page",     type="integer", example=15),
 *     @OA\Property(property="total",        type="integer", example=72),
 *     @OA\Property(property="last_page",    type="integer", example=5)
 * )
 */
final class PaginationMetaSchema implements SwaggerSchemaDefinition {}
