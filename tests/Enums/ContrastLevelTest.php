<?php

declare(strict_types=1);

use Datomatic\Color\Enums\ContrastLevel;

it('returns the WCAG minimum ratios', function (ContrastLevel $level, bool $largeText, float $ratio) {
    expect($level->minimumRatio($largeText))->toBe($ratio);
})->with([
    'AA' => [ContrastLevel::AA, false, 4.5],
    'AA large text' => [ContrastLevel::AA, true, 3.0],
    'AAA' => [ContrastLevel::AAA, false, 7.0],
    'AAA large text' => [ContrastLevel::AAA, true, 4.5],
]);
