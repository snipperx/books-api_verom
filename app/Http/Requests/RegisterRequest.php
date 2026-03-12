<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

final class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed', $this->passwordComplexityRule()],
            'device_name' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique'              => 'An account with this email address already exists.',
            'password.min'              => 'Password must be at least 12 characters.',
            'password.confirmed'        => 'Password confirmation does not match.',
            'device_name.required'      => 'A device name is required to identify this token.',
        ];
    }

    /**
     * @param Validator $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator): mixed
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422));
    }

    private function passwordComplexityRule(): string
    {
        // Regex: at least one uppercase, one lowercase, one digit, one special char
        return 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/';
    }
}
