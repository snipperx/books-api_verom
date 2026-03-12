<?php
// app/Swagger/Schemas/SuccessResponseSchema.php

declare(strict_types=1);

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="SuccessEnvelope",
 *     title="Success Envelope",
 *     required={"status"},
 *     @OA\Property(property="status", type="string", enum={"success"}, example="success")
 * )
 *
 * @OA\Schema(
 *     schema="AuthSuccessResponse",
 *     title="Auth Success Response",
 *     required={"status","message","data"},
 *     @OA\Property(property="status",  type="string", enum={"success"}, example="success"),
 *     @OA\Property(property="message", type="string", example="Authenticated successfully."),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         required={"user","token"},
 *         @OA\Property(property="user",  ref="#/components/schemas/User"),
 *         @OA\Property(property="token", ref="#/components/schemas/AuthToken")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="UserProfileResponse",
 *     title="User Profile Response",
 *     required={"status","data"},
 *     @OA\Property(property="status", type="string", enum={"success"}, example="success"),
 *     @OA\Property(property="data",   ref="#/components/schemas/User")
 * )
 *
 * @OA\Schema(
 *     schema="LogoutResponse",
 *     title="Logout Response",
 *     required={"status","message"},
 *     @OA\Property(property="status",  type="string", enum={"success"}, example="success"),
 *     @OA\Property(property="message", type="string", example="Token revoked. You have been logged out.")
 * )
 *
 * @OA\Schema(
 *     schema="LogoutAllResponse",
 *     title="Logout All Response",
 *     required={"status","message"},
 *     @OA\Property(property="status",  type="string", enum={"success"}, example="success"),
 *     @OA\Property(property="message", type="string", example="All tokens revoked. You have been logged out of all devices.")
 * )
 *
 * @OA\Schema(
 *     schema="BookDetailResponse",
 *     title="Book Detail Response",
 *     required={"status","data"},
 *     @OA\Property(property="status", type="string", enum={"success"}, example="success"),
 *     @OA\Property(property="data",   ref="#/components/schemas/Book")
 * )
 *
 * @OA\Schema(
 *     schema="BookListResponse",
 *     title="Book List Response",
 *     required={"status","data","meta"},
 *     @OA\Property(property="status", type="string", enum={"success"}, example="success"),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Book")
 *     ),
 *     @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 * )
 *
 * @OA\Schema(
 *     schema="BookSearchResponse",
 *     title="Book Search Response",
 *     required={"status","data","meta"},
 *     @OA\Property(property="status", type="string", enum={"success"}, example="success"),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Book")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         required={"total"},
 *         @OA\Property(property="total", type="integer", example=3)
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="BookCreatedResponse",
 *     title="Book Created Response",
 *     required={"status","message","data"},
 *     @OA\Property(property="status",  type="string", enum={"success"}, example="success"),
 *     @OA\Property(property="message", type="string", example="Book created successfully."),
 *     @OA\Property(property="data",    ref="#/components/schemas/Book")
 * )
 *
 * @OA\Schema(
 *     schema="BookUpdatedResponse",
 *     title="Book Updated Response",
 *     required={"status","message","data"},
 *     @OA\Property(property="status",  type="string", enum={"success"}, example="success"),
 *     @OA\Property(property="message", type="string", example="Book updated successfully."),
 *     @OA\Property(property="data",    ref="#/components/schemas/Book")
 * )
 *
 * @OA\Schema(
 *     schema="BookPatchedResponse",
 *     title="Book Patched Response",
 *     required={"status","message","data"},
 *     @OA\Property(property="status",  type="string", enum={"success"}, example="success"),
 *     @OA\Property(property="message", type="string", example="Book updated successfully."),
 *     @OA\Property(property="data",    ref="#/components/schemas/Book")
 * )
 *
 * @OA\Schema(
 *     schema="BookDeletedResponse",
 *     title="Book Deleted Response",
 *     required={"status","message"},
 *     @OA\Property(property="status",  type="string", enum={"success"}, example="success"),
 *     @OA\Property(property="message", type="string", example="Book deleted successfully.")
 * )
 *
 * @OA\Schema(
 *     schema="BookBorrowedResponse",
 *     title="Book Borrowed Response",
 *     required={"status","message","data"},
 *     @OA\Property(property="status",  type="string", enum={"success"}, example="success"),
 *     @OA\Property(property="message", type="string", example="Book borrowed successfully."),
 *     @OA\Property(property="data",    ref="#/components/schemas/Book")
 * )
 *
 * @OA\Schema(
 *     schema="BookReturnedResponse",
 *     title="Book Returned Response",
 *     required={"status","message","data"},
 *     @OA\Property(property="status",  type="string", enum={"success"}, example="success"),
 *     @OA\Property(property="message", type="string", example="Book returned successfully."),
 *     @OA\Property(property="data",    ref="#/components/schemas/Book")
 * )
 */
final class SuccessResponseSchema implements SwaggerSchemaDefinition {}
