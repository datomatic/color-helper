<?php

declare(strict_types=1);

namespace Datomatic\Color\Enums;

enum ContrastLevel
{
    case AA;

    case AAA;

    /**
     * Minimum contrast ratio required by WCAG 2.x. Large text is at least
     * 18pt (24px) regular or 14pt (18.66px) bold.
     */
    public function minimumRatio(bool $largeText = false): float
    {
        return match ($this) {
            self::AA => $largeText ? 3.0 : 4.5,
            self::AAA => $largeText ? 4.5 : 7.0,
        };
    }
}
