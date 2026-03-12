<?php


declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class InvalidCredentialsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('The provided credentials are incorrect.');
    }

    public function getStatusCode(): int
    {
        return 401;
    }

    public function getErrorCode(): string
    {
        return 'INVALID_CREDENTIALS';
    }
}
