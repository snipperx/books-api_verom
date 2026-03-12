<?php

declare(strict_types=1);

use App\Rules\ValidIsbn;

it('accepts a valid ISBN-13', function (): void {
    $rule   = new ValidIsbn();
    $failed = false;

    $rule->validate('isbn', '9780137081073', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

it('accepts a valid ISBN-10', function (): void {
    $rule   = new ValidIsbn();
    $failed = false;

    $rule->validate('isbn', '0306406152', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

it('rejects an invalid ISBN', function (): void {
    $rule   = new ValidIsbn();
    $failed = false;

    $rule->validate('isbn', '0000000000000', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

it('accepts ISBN-13 with hyphens', function (): void {
    $rule   = new ValidIsbn();
    $failed = false;

    $rule->validate('isbn', '978-0-13-708107-3', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});
