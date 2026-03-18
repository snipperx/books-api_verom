<?php


declare(strict_types=1);

namespace App\Models;

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

final class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'last_login_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'password'          => 'hashed',
        'role'              => Role::class,   // cast directly to enum
    ];

    // ─── Role Checks ──────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    public function isLibrarian(): bool
    {
        return $this->role === Role::Librarian;
    }

    public function isMember(): bool
    {
        return $this->role === Role::Member;
    }

    public function hasRole(Role $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(Role ...$roles): bool
    {
        return in_array($this->role, $roles, strict: true);
    }

    // ─── Permission Checks ────────────────────────────────────────────────────

    /**
     * Check if the user's role grants a specific permission.
     *
     * The role enum is the single source of truth —
     * no database query is required.
     */
    public function hasPermission(Permission $permission): bool
    {
        return $this->role->hasPermission($permission);
    }

    public function hasAnyPermission(Permission ...$permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    // ─── Role Management ──────────────────────────────────────────────────────

    public function assignRole(Role $role): void
    {
        $this->forceFill(['role' => $role])->save();
    }

    // ─── Token Helpers ────────────────────────────────────────────────────────

    public function hasExceededTokenLimit(): bool
    {
        return $this->tokens()->count()
            >= (int) config('sanctum.max_tokens_per_user', 10);
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
