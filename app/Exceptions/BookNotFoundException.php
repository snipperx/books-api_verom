<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class BookNotFoundException extends RuntimeException
{
    public function __construct(int $bookId)
    {
        parent::__construct("Book with ID [{$bookId}] was not found.");
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }

    public function getErrorCode(): string
    {
        return 'BOOK_NOT_FOUND';
    }
}
