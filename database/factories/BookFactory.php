<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
final class BookFactory extends Factory
{
    protected $model = Book::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'            => $this->faker->sentence(rand(2, 6), false),
            'author'           => $this->faker->name(),
            'isbn'             => $this->generateIsbn13(),
            'published_at'     => $this->faker->dateTimeBetween('-50 years', 'today')->format('Y-m-d'),
            'genre'            => $this->faker->randomElement(['fiction', 'non-fiction', 'science', 'biography', 'other']),
            'description'      => $this->faker->optional(0.8)->paragraph(3),
            'available_copies' => $this->faker->numberBetween(0, 20),
        ];
    }

    public function unavailable(): static
    {
        return $this->state(['available_copies' => 0]);
    }

    public function available(): static
    {
        return $this->state(['available_copies' => rand(1, 10)]);
    }

    private function generateIsbn13(): string
    {
        $prefix = '978';
        $body   = str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT);
        $base   = $prefix . $body;

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $base[$i] * ($i % 2 === 0 ? 1 : 3);
        }

        $checkDigit = (10 - ($sum % 10)) % 10;

        return $base . $checkDigit;
    }
}
