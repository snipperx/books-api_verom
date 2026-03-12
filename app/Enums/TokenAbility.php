<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Defines the full set of abilities a Sanctum token may be granted.
 *
 * Using an enum prevents magic string proliferation and allows
 * static analysis to catch invalid ability references at compile time.
 */
enum TokenAbility: string
{
    case ReadBooks    = 'books:read';
    case WriteBooks   = 'books:write';
    case BorrowBooks  = 'books:borrow';
    case ManageTokens = 'tokens:manage';

    /**
     * @return list<string>
     */
    public static function allAbilities(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return list<string>
     */
    public static function readerAbilities(): array
    {
        return [
            self::ReadBooks->value,
            self::BorrowBooks->value,
        ];
    }
}
