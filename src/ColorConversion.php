<?php

namespace Datomatic\Color;


class ColorConversion
{

    /**
     * @param array<string,int> $rbg
     * @return string
     */
    public static function rgbToHex(array $rbg): string
    {
        return sprintf('%02x%02x%02x', ...array_values($rbg));
    }

    /**
     * @param string $hex
     * @return array<string,int>
     */
    public static function hexToRgb(string $hex): array
    {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return ['r' => (int)round($r), 'g' => (int)round($g), 'b' => (int)round($b)];
    }

    /**
     * @param int $r
     * @param int $b
     * @param int $g
     * @return array<string,int>
     */
    public static function rgbToHsv(int $r, int $b, int $g): array
    {
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $delta = $max - $min;

        if (!$delta) {
            $h = 0;
        } elseif ($r === $max) {
            $h = 60 * ((($g - $b) / $delta) % 6);
        } elseif ($g === $max) {
            $h = 60 * ((($b - $r) / $delta) + 2);
        } else {
            $h = 60 * ((($r - $g) / $delta) + 4);
        }

        $s = (bool)($max ? $delta / $max : 0);

        $v = $max;

        return ['h' => (int)floor($h), 's' => (int)floor($s), 'v' => (int)floor($v)];
    }

    /**
     * @param int $h
     * @param int $s
     * @param int $v
     * @return array<string,int>
     */
    public static function hsvToRgb(int $h, int $s, int $v): array
    {
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
            $rgb[$i] = (int)round(($rgb[$i] + ($max - $rgb[$i]) * (1 - $s / 100)) * $factor);
        }

        return ['r' => (int)$rgb[0], 'g' => (int)$rgb[1], 'b' => (int)$rgb[2]];
    }

    /**
     * @param int $r
     * @param int $g
     * @param int $b
     * @return array<string,int>
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
            $h = 0;
            switch ($max) {
                case $r:
                    $h = 60 * fmod((($g - $b) / $d), 6);
                    if ($b > $g) {
                        $h += 360;
                    }
                    break;

                case $g:
                    $h = 60 * (($b - $r) / $d + 2);
                    break;

                case $b:
                    $h = 60 * (($r - $g) / $d + 4);
                    break;
            }
        }

        return ['h' => (int)round($h), 's' => (int)round($s), 'l' => (int)round($l)];
    }

    /**
     * @param int $h
     * @param int $s
     * @param int $l
     * @return array<string,int>
     */
    public static function hslToRgb(int $h, int $s, int $l): array
    {
        $h /= 60;
        if ($h < 0) {
            $h = 6 - fmod(-$h, 6);
        }
        $h = fmod($h, 6);

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
        $r = (int)round(($r + $m) * 255);
        $g = (int)round(($g + $m) * 255);
        $b = (int)round(($b + $m) * 255);

        return ['r' => $r, 'g' => $g, 'b' => $b];
    }

    /**
     * @param $r
     * @param $g
     * @param $b
     * @return array<string,int>
     */
    public static function rgbToCmyk(int $r, int $g, int $b): array
    {
        $c = (255 - $r) / 255.0 * 100;
        $m = (255 - $g) / 255.0 * 100;
        $y = (255 - $b) / 255.0 * 100;

        $k = min([$c, $m, $y]);
        $c = $c - $k;
        $m = $m - $k;
        $y = $y - $k;

        return ['c' => (int)round($c), 'm' => (int)round($m), 'y' => (int)round($y), 'k' => (int)round($k)];
    }

    /**
     * @param int $c
     * @param int $m
     * @param int $y
     * @param int $k
     * @return array<string,int>
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

        $r = (int)round($r * 255);
        $g = (int)round($g * 255);
        $b = (int)round($b * 255);

        return ['r' => $r, 'g' => $g, 'b' => $b];
    }
}
