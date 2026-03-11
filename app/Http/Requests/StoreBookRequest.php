<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\ValidIsbn;
use Illuminate\Foundation\Http\FormRequest;

final class StoreBookRequest extends FormRequest
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
        return [
            'title'            => ['required', 'string', 'max:255'],
            'author'           => ['required', 'string', 'max:255'],
            'isbn'             => ['required', 'string', 'unique:books,isbn', new ValidIsbn()],
            'published_at'     => ['required', 'date', 'before_or_equal:today'],
            'genre'            => ['required', 'in:fiction,non-fiction,science,biography,other'],
            'description'      => ['nullable', 'string', 'max:2000'],
            'available_copies' => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'published_at.before_or_equal' => 'The publication date cannot be in the future.',
            'isbn.unique'                  => 'A book with this ISBN already exists.',
        ];
    }
}
