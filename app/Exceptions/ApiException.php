<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

abstract class ApiException extends RuntimeException
{
    abstract public function getStatusCode(): int;

    abstract public function getErrorCode(): string;
}
