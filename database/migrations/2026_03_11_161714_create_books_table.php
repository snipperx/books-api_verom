<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table): void {
            $table->id();
            $table->string('ulid', 26)->unique();
            $table->string('title', 255);
            $table->string('author', 255);
            $table->string('isbn', 20)->unique();
            $table->date('published_at');
            $table->enum('genre', [
                'fiction',
                'non-fiction',
                'science',
                'biography',
                'other',
            ])->default('other');
            $table->text('description')->nullable();
            $table->unsignedInteger('available_copies')->default(0);
            $table->softDeletes();
            $table->timestamps();

            // Performance indexes
            $table->index('genre');
            $table->index('author');
            $table->index('published_at');
            $table->index('available_copies');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
