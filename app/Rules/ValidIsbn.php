<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class ValidIsbn implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isbn = preg_replace('/[\s\-]/', '', (string) $value);

        if (!$this->isValidIsbn10($isbn) && !$this->isValidIsbn13($isbn)) {
            $fail("The :attribute must be a valid ISBN-10 or ISBN-13.");
        }
    }

    private function isValidIsbn10(string $isbn): bool
    {
        if (strlen($isbn) !== 10) {
            return false;
        }

        if (!preg_match('/^\d{9}[\dX]$/', $isbn)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $isbn[$i] * (10 - $i);
        }

        $checkDigit = strtoupper($isbn[9]);
        $sum += ($checkDigit === 'X') ? 10 : (int) $checkDigit;

        return $sum % 11 === 0;
    }

    private function isValidIsbn13(string $isbn): bool
    {
        if (strlen($isbn) !== 13) {
            return false;
        }

        if (!preg_match('/^\d{13}$/', $isbn)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $isbn[$i] * ($i % 2 === 0 ? 1 : 3);
        }

        $checkDigit = (10 - ($sum % 10)) % 10;

        return $checkDigit === (int) $isbn[12];
    }
}
