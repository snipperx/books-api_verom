<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class TokenLimitExceededException extends RuntimeException
{
    public function __construct(int $limit)
    {
        parent::__construct(
            "You have reached the maximum number of active tokens ({$limit}). "
            . "Please revoke an existing token before creating a new one."
        );
    }

    public function getStatusCode(): int
    {
        return 422;
    }

    public function getErrorCode(): string
    {
        return 'TOKEN_LIMIT_EXCEEDED';
    }
}
