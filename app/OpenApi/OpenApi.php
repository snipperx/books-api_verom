<?php

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="Laravel API",
 *         version="1.0.0",
 *         description="API documentation for the Laravel application"
 *     ),
 *     @OA\Server(
 *         url=L5_SWAGGER_CONST_HOST,
 *         description="API Server"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
