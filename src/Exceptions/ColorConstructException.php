<?php

declare(strict_types=1);

namespace Datomatic\Color\Exceptions;

use InvalidArgumentException;

class ColorConstructException extends InvalidArgumentException
{
    public static function invalidHexFormat(string $hex): self
    {
        return new self("HEX color `{$hex}` doesn't match the correct format");
    }

    public static function invalidHexLength(string $hex): self
    {
        return new self("HEX color `{$hex}` needs to be 3, 4, 6 or 8 digits long");
    }

    public static function invalidIntegerValue(string $param, int $min, int $max): self
    {
        return new self("Input param `{$param}` must be between `{$min}` and `{$max}`");
    }

    public static function invalidFloatValue(string $param, float $min, float $max): self
    {
        return new self("Input param `{$param}` must be between `{$min}` and `{$max}`");
    }

    public static function invalidColorName(string $name): self
    {
        return new self("Color name `{$name}` was not recognized");
    }

    public static function invalidColorString(string $color): self
    {
        return new self("Color `{$color}` could not be parsed");
    }

    public static function emptyColorList(): self
    {
        return new self('At least one color is required');
    }
}
