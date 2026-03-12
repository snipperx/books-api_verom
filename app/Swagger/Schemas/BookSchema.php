<?php

declare(strict_types=1);

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Book",
 *     title="Book",
 *     description="A book resource in the library system",
 *     required={"id","title","author","isbn","published_at","genre","available_copies"},
 *
 *     @OA\Property(property="id",               type="integer", example=1,
 *                  description="Auto-incrementing primary key"),
 *     @OA\Property(property="title",            type="string",  example="Clean Code",
 *                  maxLength=255),
 *     @OA\Property(property="author",           type="string",  example="Robert C. Martin",
 *                  maxLength=255),
 *     @OA\Property(property="isbn",             type="string",  example="9780132350884",
 *                  description="Valid ISBN-10 or ISBN-13"),
 *     @OA\Property(property="published_at",     type="string",  format="date",
 *                  example="2008-08-01",
 *                  description="Publication date — cannot be in the future"),
 *     @OA\Property(property="genre",            type="string",
 *                  enum={"fiction","non-fiction","science","biography","other"},
 *                  example="non-fiction"),
 *     @OA\Property(property="description",      type="string",  nullable=true,
 *                  maxLength=2000,
 *                  example="A handbook of agile software craftsmanship."),
 *     @OA\Property(property="available_copies", type="integer", minimum=0, example=5),
 *     @OA\Property(property="is_available",     type="boolean", example=true,
 *                  description="Convenience flag — true when available_copies > 0"),
 *     @OA\Property(property="created_at",       type="string",  format="date-time",
 *                  example="2024-01-15T09:30:00+00:00"),
 *     @OA\Property(property="updated_at",       type="string",  format="date-time",
 *                  example="2024-06-01T14:22:00+00:00")
 * )
 *
 * @OA\Schema(
 *     schema="StoreBookRequest",
 *     title="Store Book Request",
 *     required={"title","author","isbn","published_at","genre","available_copies"},
 *
 *     @OA\Property(property="title",            type="string",  example="Clean Code",      maxLength=255),
 *     @OA\Property(property="author",           type="string",  example="Robert C. Martin", maxLength=255),
 *     @OA\Property(property="isbn",             type="string",  example="9780132350884"),
 *     @OA\Property(property="published_at",     type="string",  format="date", example="2008-08-01"),
 *     @OA\Property(property="genre",            type="string",
 *                  enum={"fiction","non-fiction","science","biography","other"},
 *                  example="non-fiction"),
 *     @OA\Property(property="description",      type="string",  nullable=true,
 *                  example="A handbook of agile software craftsmanship.", maxLength=2000),
 *     @OA\Property(property="available_copies", type="integer", minimum=0, example=5)
 * )
 *
 * @OA\Schema(
 *     schema="UpdateBookRequest",
 *     title="Update Book Request",
 *     description="All fields required for PUT — full replacement",
 *     required={"title","author","isbn","published_at","genre","available_copies"},
 *
 *     @OA\Property(property="title",            type="string",  example="Clean Code",      maxLength=255),
 *     @OA\Property(property="author",           type="string",  example="Robert C. Martin", maxLength=255),
 *     @OA\Property(property="isbn",             type="string",  example="9780132350884"),
 *     @OA\Property(property="published_at",     type="string",  format="date", example="2008-08-01"),
 *     @OA\Property(property="genre",            type="string",
 *                  enum={"fiction","non-fiction","science","biography","other"},
 *                  example="non-fiction"),
 *     @OA\Property(property="description",      type="string",  nullable=true, maxLength=2000),
 *     @OA\Property(property="available_copies", type="integer", minimum=0, example=5)
 * )
 *
 * @OA\Schema(
 *     schema="PatchBookRequest",
 *     title="Patch Book Request",
 *     description="All fields optional for PATCH — partial update",
 *
 *     @OA\Property(property="title",            type="string",  example="Clean Code",      maxLength=255),
 *     @OA\Property(property="author",           type="string",  example="Robert C. Martin", maxLength=255),
 *     @OA\Property(property="isbn",             type="string",  example="9780132350884"),
 *     @OA\Property(property="published_at",     type="string",  format="date", example="2008-08-01"),
 *     @OA\Property(property="genre",            type="string",
 *                  enum={"fiction","non-fiction","science","biography","other"}),
 *     @OA\Property(property="description",      type="string",  nullable=true, maxLength=2000),
 *     @OA\Property(property="available_copies", type="integer", minimum=0)
 * )
 */
final class BookSchema implements SwaggerSchemaDefinition {}
