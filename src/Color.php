<?php

namespace Datomatic\Color;

use Datomatic\Color\Exceptions\ColorConstructException;

class Color
{
    protected string $hex = '';

    /** @var array<string,int> */
    protected array $hsl = [];

    /** @var array<string,int> */
    protected array $hsv = [];

    /** @var array<string,int> */
    protected array $cmyk = [];

    /** @param array<string,int> $rgb */
    public function __construct(protected array $rgb)
    {
    }

    /* From methods */

    /**
     * @param string $name
     * @return Color
     *
     * @throws ColorConstructException
     */
    public static function fromName(string $name): Color
    {
        return isset(ColorNames::COLORS[$name]) ? self::fromHex(ColorNames::COLORS[$name]) : throw ColorConstructException::invalidColorName($name);
    }

    /**
     * @param int $r
     * @param int $g
     * @param int $b
     * @return Color
     */
    public static function fromRgb(int $r, int $g, int $b): Color
    {
        $rgb = self::sanitizeRgb($r, $g, $b);

        return new Color($rgb);
    }

    /**
     * @param string $hex
     * @return Color
     *
     * @throws ColorConstructException
     */
    public static function fromHex(string $hex): Color
    {
        $hex = self::sanitizeHex($hex);
        $rgb = self::hexToRgb($hex);

        return (new Color($rgb))->setHex($hex);
    }

    /**
     * @param int $h
     * @param int $s
     * @param int $v
     * @return Color
     */
    public static function fromHsv(int $h, int $s, int $v): Color
    {
        $hsv = self::sanitizeHsv($h, $s, $v);
        $rgb = self::hsvToRgb(...$hsv);

        return (new Color($rgb))->setHsv($hsv);
    }

    /**
     * @param int $h
     * @param int $s
     * @param int $l
     * @return Color
     */
    public static function fromHsl(int $h, int $s, int $l): Color
    {
        $hsl = self::sanitizeHsl($h, $s, $l);
        $rgb = self::hslToRgb(...$hsl);

        return (new Color($rgb))->setHsl($hsl);
    }

    /**
     * @param int $c
     * @param int $m
     * @param int $y
     * @param int $k
     * @return Color
     */
    public static function fromCmyk(int $c, int $m, int $y, int $k): Color
    {
        $rgb = self::cmykToRgb($c, $m, $y, $k);

        return (new Color($rgb))->setCmyk(['c' => $c, 'm' => $m, 'y' => $y, 'k' => $k]);
    }

    /* protected Setter methods */

    protected function setHex(?string $hex = null): self
    {
        $this->hex = $hex ?? self::rgbToHex($this->rgb);

        return $this;
    }

    /**  @param null|array<string,int> $hsv */
    protected function setHsv(?array $hsv = null): self
    {
        $this->hsv = $hsv ?? self::rgbToHsv(...$this->rgb);

        return $this;
    }

    /**  @param null|array<string,int> $hsl */
    protected function setHsl(?array $hsl = null): self
    {
        $this->hsl = $hsl ?? self::rgbToHsl(...$this->rgb);

        return $this;
    }

    /**  @param null|array<string,int> $cmyk */
    protected function setCmyk(?array $cmyk = null): self
    {
        $this->cmyk = $cmyk ?? self::rgbToCmyk(...$this->rgb);

        return $this;
    }

    /* Export methods */

    /**
     * @return array<string,int>
     */
    public function rgb(): array
    {
        return $this->rgb;
    }

    /**
     * @return string
     */
    public function hex(): string
    {
        if (!$this->hex) {
            $this->setHex();
        }

        return '#' . $this->hex;
    }

    /**
     * @return array<string,int>
     */
    public function hsv(): array
    {
        if (!$this->hsv) {
            $this->setHsv();
        }

        return $this->hsv;
    }

    /**
     * @return array<string,int>
     */
    public function hsl(): array
    {
        if (!$this->hsl) {
            $this->setHsl();
        }

        return $this->hsl;
    }

    /**
     * @return array<string,int>
     */
    public function cmyk(): array
    {
        if (!$this->cmyk) {
            $this->setCmyk();
        }

        return $this->cmyk;
    }

    public function __toString(): string
    {
        return $this->hex();
    }

    /* Conversion static methods */

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
     *
     * @throws ColorConstructException
     */
    public static function hexToRgb(string $hex): array
    {
        /** @var string $hex */
        $hex = self::sanitizeHex($hex);

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

        $s = (bool)$max ? $delta / $max : 0;

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
        //desaturate by increasing lower levels
        $max = max($rgb);
        $factor = 255 * ($v / 100);
        for ($i = 0; $i < 3; $i++) {
            //use distance between 0 and max (1) and multiply with value
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
        $oldR = $r;
        $oldG = $g;
        $oldB = $b;

        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $l = ($max + $min) / 2;
        $d = $max - $min;

        if ($d == 0) {
            $h = $s = 0; // achromatic
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

        return ['c' => (int) round($c), 'm' => (int) round($m), 'y' => (int) round($y), 'k' => (int) round($k)];
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

    private static function checkRange(int $number, int $min, int $max): bool
    {
        if ($number >= $min && $number <= $max) {
            return true;
        }

        throw ColorConstructException::invalidIntegerValue(0, 255);
    }

    /**
     * @param int $r
     * @param int $g
     * @param int $b
     * @return array<string,int>
     */
    protected static function sanitizeRgb(int $r, int $g, int $b): array
    {
        foreach (['r', 'g', 'b'] as $param) {
            self::checkRange(${$param}, 0, 255);
        }

        return ['r' => $r, 'g' => $g, 'b' => $b];
    }

    /**
     * @param string $hex
     * @return string
     *
     * @throws ColorConstructException
     */
    protected static function sanitizeHex(string $hex): string
    {
        $hex = strtolower(str_replace('#', '', $hex));

        if (!preg_match('/^[a-fA-F0-9]+$/', $hex)) {
            throw ColorConstructException::invalidHexFormat($hex);
        }

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        } elseif (strlen($hex) !== 6) {
            throw ColorConstructException::invalidHexLenght($hex);
        }

        return $hex;
    }

    /**
     * @param int $h
     * @param int $s
     * @param int $v
     * @return array<string,int>
     *
     * @throws ColorConstructException
     */
    protected static function sanitizeHsv(int $h, int $s, int $v): array
    {
        $h %= 360;
        self::checkRange($h, 0, 360);
        self::checkRange($s, 0, 100);
        self::checkRange($v, 0, 100);

        return ['h' => $h, 's' => $s, 'v' => $v];
    }

    /**
     * @param int $h
     * @param int $s
     * @param int $l
     * @return array<string,int>
     *
     * @throws ColorConstructException
     */
    protected static function sanitizeHsl(int $h, int $s, int $l): array
    {
        $h %= 360;
        self::checkRange($h, 0, 360);
        self::checkRange($s, 0, 100);
        self::checkRange($l, 0, 100);

        return ['h' => $h, 's' => $s, 'l' => $l];
    }

    /**
     * @param int $c
     * @param int $m
     * @param int $y
     * @param int $k
     * @return array<string,int>
     *
     * @throws ColorConstructException
     */
    protected static function sanitizeCmyk(int $c, int $m, int $y, int $k): array
    {
        self::checkRange($c, 0, 100);
        self::checkRange($m, 0, 100);
        self::checkRange($y, 0, 100);
        self::checkRange($k, 0, 100);

        return ['c' => $c, 'm' => $m, 'y' => $y, 'k' => $k];
    }

    /**
     * @return float
     */
    public function luminosity(): float
    {
        return 0.2126 * pow($this->rgb['r'] / 255, 2.2) +
            0.7152 * pow($this->rgb['g'] / 255, 2.2) +
            0.0722 * pow($this->rgb['b'] / 255, 2.2);
    }

    public static function contrastColors(Color $color1, Color $color2): float
    {
        $l1 = $color1->luminosity();
        $l2 = $color2->luminosity();

        if ($l1 > $l2) {
            return ($l1 + 0.05) / ($l2 + 0.05);
        } else {
            return ($l2 + 0.05) / ($l1 + 0.05);
        }
    }

    /**
     * @param Color $color
     * @return float
     */
    public function contrast(Color $color): float
    {
        return self::contrastColors($this, $color);
    }

    /**
     * @param array<Color> $colors
     * @return Color
     */
    public function bestContrastColor(array $colors): Color
    {
        $bestColor = $colors[0];
        $bestContrast = 0;
        foreach ($colors as $color) {
            $contrast = $this->contrast($color);
            if ($contrast > $bestContrast) {
                $bestColor = $color;
            }
        }

        return $bestColor;
    }

    /**
     * @return Color
     */
    public static function random(): Color
    {
        return new Color(['r' => rand(0,255), 'g' => rand(0,255), 'b' => rand(0,255)]);
    }

    /**
     * @param Color $color1
     * @param Color $color2
     * @param float $ratio
     * @return Color
     */
    public static function mixColors(Color $color1, Color $color2, float $ratio = 0.5): Color
    {
        $rgb1 = $color1->rgb();
        $rgb2 = $color2->rgb();

        $r = (int) round($rgb1['r']*(1-$ratio)+$rgb2['r']*$ratio);
        $g = (int) round($rgb1['g']*(1-$ratio)+$rgb2['g']*$ratio);
        $b = (int) round($rgb1['b']*(1-$ratio)+$rgb2['b']*$ratio);

        return new Color(['r' => $r, 'g' => $g, 'b' => $b]);
    }

    /**
     * @param Color $color
     * @param float $ratio
     * @return Color
     */
    public function mix(Color $color, float $ratio = 0.5): Color
    {
        return self::mixColors($this, $color, $ratio);
    }



    /**
     * @param array<Color> $colors
     * @return Color
     */
    public static function averageColors(array $colors): Color
    {
        $r = $g = $b = 0;

        foreach($colors as $color){
            $rgb = $color->rgb();
            $r += $rgb['r'];
            $g += $rgb['g'];
            $b += $rgb['b'];
        }

        $count = count($colors);
        $r /= $count;
        $g /= $count;
        $b /= $count;

        return new Color(['r' => (int) round($r), 'g' => (int) round($g), 'b' => (int) round($b)]);
    }

}
