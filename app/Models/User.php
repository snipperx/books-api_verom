<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable , SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'password'          => 'hashed',
    ];

    // ─── Domain Behaviour ─────────────────────────────────────────────────────

    public function hasExceededTokenLimit(): bool
    {
        $limit = (int) config('sanctum.max_tokens_per_user', 10);

        return $this->tokens()->count() >= $limit;
    }

    public function revokeAllTokens(): void
    {
        $this->tokens()->delete();
    }

    public function revokeCurrentToken(): void
    {
        $this->currentAccessToken()->delete();
    }

    public function updateLastLogin(): void
    {
        $this->forceFill(['last_login_at' => now()])->save();
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function borrowLogs(): HasMany
    {
        return $this->hasMany(BorrowLog::class);
    }
}
