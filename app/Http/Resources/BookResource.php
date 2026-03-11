<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



final class BookResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'author'           => $this->author,
            'isbn'             => $this->isbn,
            'published_at'     => $this->published_at?->toDateString(),
            'genre'            => $this->genre,
            'description'      => $this->description,
            'available_copies' => $this->available_copies,
            'is_available'     => $this->isAvailable(),
            'created_at'       => $this->created_at?->toIso8601String(),
            'updated_at'       => $this->updated_at?->toIso8601String(),
        ];
    }
}
