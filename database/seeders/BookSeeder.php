<?php


declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

final class BookSeeder extends Seeder
{
    public function run(): void
    {
        Book::factory()->count(50)->create();

        // Ensure a variety of states for testing
        Book::factory()->count(10)->unavailable()->create();
        Book::factory()->count(10)->available()->create();
    }
}
