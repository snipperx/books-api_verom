<?php


declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\ValidIsbn;
use Illuminate\Foundation\Http\FormRequest;

final class PatchBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $bookId = $this->route('book')?->id;

        return [
            'title'            => ['sometimes', 'string', 'max:255'],
            'author'           => ['sometimes', 'string', 'max:255'],
            'isbn'             => ['sometimes', 'string', "unique:books,isbn,{$bookId}", new ValidIsbn()],
            'published_at'     => ['sometimes', 'date', 'before_or_equal:today'],
            'genre'            => ['sometimes', 'in:fiction,non-fiction,science,biography,other'],
            'description'      => ['sometimes', 'nullable', 'string', 'max:2000'],
            'available_copies' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
