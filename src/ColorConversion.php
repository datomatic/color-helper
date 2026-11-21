<?php

declare(strict_types=1);

namespace Datomatic\Color;

class ColorConversion
{
    public static function normalizeHue(int $hue): int
    {
        return (($hue % 360) + 360) % 360;
    }

    /**
     * Rounds half up consistently on every PHP version: PHP 8.4 changed round()
     * for values like 25.499999999999996, which are 25.5 before the float error.
     */
    public static function roundToInt(float|int $value): int
    {
        return (int) round(round($value, 6));
    }

    public static function rgbToHex(int $r, int $g, int $b): string
    {
        return sprintf('%02x%02x%02x', $r, $g, $b);
    }

    /**
     * @return array{r: int, g: int, b: int}
     */
    public static function hexToRgb(string $hex): array
    {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return ['r' => self::roundToInt($r), 'g' => self::roundToInt($g), 'b' => self::roundToInt($b)];
    }

    /**
     * @return array{h: int, s: int, v: int}
     */
    public static function rgbToHsv(int $r, int $g, int $b): array
    {
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $delta = $max - $min;

        $v = 100 * $max;

        if ($delta == 0) {
            $h = 0;
            $s = 0;
        } else {
            $s = 100 * $delta / $max;

            $h = 60 * match ($min) {
                $r => 3 - (($g - $b) / $delta),
                $b => 1 - (($r - $g) / $delta),
                default => 5 - (($b - $r) / $delta),
            };
        }

        return ['h' => self::normalizeHue(self::roundToInt($h)), 's' => self::roundToInt($s), 'v' => self::roundToInt($v)];
    }

    /**
     * @return array{r: int, g: int, b: int}
     */
    public static function hsvToRgb(int $h, int $s, int $v): array
    {
        $h = self::normalizeHue($h);
        $rgb = [0, 0, 0];

        for ($i = 0; $i < 4; $i++) {
            if (abs($h - $i * 120) < 120) {
                $distance = max(60, abs($h - $i * 120));
                $rgb[$i % 3] = 1 - (($distance - 60) / 60);
            }
        }

        $max = max($rgb);
        $factor = 255 * ($v / 100);
        for ($i = 0; $i < 3; $i++) {
            $rgb[$i] = self::roundToInt(($rgb[$i] + ($max - $rgb[$i]) * (1 - $s / 100)) * $factor);
        }

        return ['r' => (int) $rgb[0], 'g' => (int) $rgb[1], 'b' => (int) $rgb[2]];
    }

    /**
     * @return array{h: int, s: int, l: int}
     */
    public static function rgbToHsl(int $r, int $g, int $b): array
    {
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $l = ($max + $min) / 2;
        $d = $max - $min;

        if ($d == 0) {
            $h = $s = 0;
        } else {
            $s = $d / (1 - abs(2 * $l - 1));
            $h = match ($max) {
                $r => 60 * fmod(($g - $b) / $d, 6) + ($b > $g ? 360 : 0),
                $g => 60 * (($b - $r) / $d + 2),
                default => 60 * (($r - $g) / $d + 4),
            };
        }

        return ['h' => self::normalizeHue(self::roundToInt($h)), 's' => self::roundToInt($s * 100), 'l' => self::roundToInt($l * 100)];
    }

    /**
     * @return array{r: int, g: int, b: int}
     */
    public static function hslToRgb(int $h, int $s, int $l): array
    {
        $h = self::normalizeHue($h) / 60;

        $s = max(0, min(1, $s / 100));
        $l = max(0, min(1, $l / 100));

        $c = (1 - abs((2 * $l) - 1)) * $s;
        $x = $c * (1 - abs(fmod($h, 2) - 1));

        if ($h < 1) {
            $r = $c;
            $g = $x;
            $b = 0;
        } elseif ($h < 2) {
            $r = $x;
            $g = $c;
            $b = 0;
        } elseif ($h < 3) {
            $r = 0;
            $g = $c;
            $b = $x;
        } elseif ($h < 4) {
            $r = 0;
            $g = $x;
            $b = $c;
        } elseif ($h < 5) {
            $r = $x;
            $g = 0;
            $b = $c;
        } else {
            $r = $c;
            $g = 0;
            $b = $x;
        }

        $m = $l - $c / 2;
        $r = self::roundToInt(($r + $m) * 255);
        $g = self::roundToInt(($g + $m) * 255);
        $b = self::roundToInt(($b + $m) * 255);

        return ['r' => $r, 'g' => $g, 'b' => $b];
    }

    /**
     * @return array{c: int, m: int, y: int, k: int}
     */
    public static function rgbToCmyk(int $r, int $g, int $b): array
    {
        $c = 255 - $r;
        $m = 255 - $g;
        $y = 255 - $b;

        $k = min($c, $m, $y);
        if ($k === 255) {
            $c = $m = $y = 0;
        } else {
            $c = ($c - $k) / (255 - $k) * 255;
            $m = ($m - $k) / (255 - $k) * 255;
            $y = ($y - $k) / (255 - $k) * 255;
        }

        return [
            'c' => self::roundToInt($c / 255 * 100),
            'm' => self::roundToInt($m / 255 * 100),
            'y' => self::roundToInt($y / 255 * 100),
            'k' => self::roundToInt($k / 255 * 100), ];
    }

    /**
     * @return array{r: int, g: int, b: int}
     */
    public static function cmykToRgb(int $c, int $m, int $y, int $k): array
    {
        $c /= 100;
        $m /= 100;
        $y /= 100;
        $k /= 100;

        $r = 1 - ($c * (1 - $k)) - $k;
        $g = 1 - ($m * (1 - $k)) - $k;
        $b = 1 - ($y * (1 - $k)) - $k;

        $r = self::roundToInt($r * 255);
        $g = self::roundToInt($g * 255);
        $b = self::roundToInt($b * 255);

        return ['r' => $r, 'g' => $g, 'b' => $b];
    }
}
