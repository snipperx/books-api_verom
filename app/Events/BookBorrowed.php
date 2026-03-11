<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Book;
use Illuminate\Foundation\Events\Dispatchable;

final class BookBorrowed
{
    use Dispatchable;

    /**
     * @param Book $book
     * @param int $userId
     */
    public function __construct(
        public readonly Book $book,
        public readonly int $userId,
    ) {}
}
