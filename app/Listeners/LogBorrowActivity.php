<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\BookBorrowed;
use App\Events\BookReturned;
use App\Models\BorrowLog;

final class LogBorrowActivity
{
    public function handleBorrowed(BookBorrowed $event): void
    {
        $this->log($event->book->id, $event->userId, 'borrowed');
    }

    public function handleReturned(BookReturned $event): void
    {
        $this->log($event->book->id, $event->userId, 'returned');
    }

    private function log(int $bookId, int $userId, string $action): void
    {
        BorrowLog::create([
            'book_id'     => $bookId,
            'user_id'     => $userId,
            'action'      => $action,
            'actioned_at' => now(),
        ]);
    }
}
