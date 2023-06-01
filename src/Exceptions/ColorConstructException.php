<?php

namespace Datomatic\Color\Exceptions;

use Exception;

final class ColorConstructException extends Exception
{
    public static function invalidHexFormat(string $hex): self
    {
        return new self("HEX color `{$hex}` doesn't match the correct format");
    }

    public static function invalidHexLenght(string $hex): self
    {
        return new self("HEX color `{$hex}` needs to be 6 or 3 digits long");
    }

    public static function invalidIntegerValue(string $param, int $min, int $max): self
    {
        return new self("Input param `{$param}` must be between `{$min}` and `{$max}`");
    }

    public static function invalidColorName(string $name): self
    {
        return new self("Color name `{$name}` was not recognized");
    }
}
