<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

final class Book extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'published_at',
        'genre',
        'description',
        'available_copies',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'published_at'    => 'date',
        'available_copies' => 'integer',
    ];

    /**
     * @var list<string>
     */
    protected $dates = ['deleted_at'];

    public function isAvailable(): bool
    {
        return $this->available_copies > 0;
    }

    public function borrowLogs(): HasMany
    {
        return $this->hasMany(BorrowLog::class);
    }

    /**
     * @return void
     */
    protected static function booted(): void
    {
        Book::creating(function ($book) {
            $book->ulid = (string) Str::ulid();
        });
    }

}
