<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class BookNotAvailableException extends RuntimeException
{
    public function __construct(int $bookId)
    {
        parent::__construct("Book with ID [{$bookId}] has no available copies.");
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    public function getErrorCode(): string
    {
        return 'BOOK_NOT_AVAILABLE';
    }
}
