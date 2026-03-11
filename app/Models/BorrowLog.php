<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class BorrowLog extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'book_id',
        'user_id',
        'action',
        'actioned_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'actioned_at' => 'datetime',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
