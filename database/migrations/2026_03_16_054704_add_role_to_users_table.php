<?php

declare(strict_types=1);

use App\Enums\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')
                ->default(Role::Member->value)
                ->after('email')
                ->comment('Single role per user — admin, librarian, or member');

            $table->softDeletes();
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['role', 'last_login_at']);
            $table->dropSoftDeletes();
        });
    }
};
