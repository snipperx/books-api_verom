<?php
// app/Swagger/OpenApiDefinition.php

declare(strict_types=1);

namespace App\Swagger;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Book Library API",
 *         description="Production-quality RESTful API for a Book Library system.",
 *         @OA\Contact(
 *             email="support@booklibrary.dev",
 *             name="API Support"
 *         ),
 *         @OA\License(
 *             name="MIT",
 *             url="https://opensource.org/licenses/MIT"
 *         )
 *     ),
 *     @OA\Server(
 *         url="http://localhost:8000",
 *         description="Local development"
 *     ),
 *     @OA\Server(
 *         url="https://staging.booklibrary.dev",
 *         description="Staging"
 *     ),
 *     @OA\Server(
 *         url="https://api.booklibrary.dev",
 *         description="Production"
 *     ),
 *     @OA\ExternalDocumentation(
 *         description="GitHub Repository",
 *         url="https://github.com/your-org/book-library-api"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token",
 *     description="Obtain a token via POST /api/v1/auth/login or POST /api/v1/auth/register."
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Register, login, logout, and user profile endpoints"
 * )
 * @OA\Tag(
 *     name="Books",
 *     description="CRUD, search, borrow and return operations for books"
 * )
 */
final class OpenApiDefinition {}
