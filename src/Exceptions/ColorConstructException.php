<?php

namespace Datomatic\Color\Exceptions;

use Exception;

final class ColorConstructException extends Exception
{
    public static function invalidHexFormat(string $hex): self
    {
        return new static("HEX color `{$hex}` doesn't match the correct format");
    }

    public static function invalidHexLenght(string $hex): self
    {
        return new static("HEX color `{$hex}` needs to be 6 or 3 digits long");
    }

    public static function invalidIntegerValue(int $min, int $max): self
    {
        return new static("Input param must be between `{$min}` and `{$max}`");
    }

    public static function invalidColorName(string $name): self
    {
        return new static("Color name `{$name}` was not recognized");
    }
}
