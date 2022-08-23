<?php

namespace Datomatic\Color;

use Datomatic\Color\Exceptions\ColorConstructException;

class Color
{
    /** @var array<string,int> */
    protected array $rgb = [];

    protected string $hex = '';

    /** @var array<string,int> */
    protected array $hsl = [];

    /** @var array<string,int> */
    protected array $hsv = [];

    /** @var array<string,int> */
    protected array $cmyk = [];

    /** @param array<string,int> $rgb */
    public function __construct(int $r, int $g, int $b)
    {
        $rgb = self::sanitizeRgb($r, $g, $b);

        $this->rgb = $rgb;
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
        if (isset(ColorNames::COLORS[$name])) {
            return self::fromHex(ColorNames::COLORS[$name]);
        }

        throw ColorConstructException::invalidColorName($name);
    }

    /**
     * @param int $r
     * @param int $g
     * @param int $b
     * @return Color
     */
    public static function fromRgb(int $r, int $g, int $b): Color
    {
        return new Color($r, $g, $b);
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
        $rgb = ColorConversion::hexToRgb($hex);

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
        $rgb = ColorConversion::hsvToRgb(...$hsv);

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
        $rgb = ColorConversion::hslToRgb(...$hsl);

        return (new Color($rgb))->setHsl($hsl);
    }

    /**
     * @param int $c
     * @param int $m
     * @param int $y
     * @param int $k
     * @return Color
     * @throws ColorConstructException
     */
    public static function fromCmyk(int $c, int $m, int $y, int $k): Color
    {
        $cmyk = self::sanitizeCmyk($c, $m, $y, $k);
        $rgb = ColorConversion::cmykToRgb(...$cmyk);

        return (new Color($rgb))->setCmyk(['c' => $c, 'm' => $m, 'y' => $y, 'k' => $k]);
    }


    /* protected Setter methods */

    protected function setHex(?string $hex = null): self
    {
        $this->hex = $hex ?? ColorConversion::rgbToHex($this->rgb);

        return $this;
    }

    /**  @param null|array<string,int> $hsv */
    protected function setHsv(?array $hsv = null): self
    {
        $this->hsv = $hsv ?? ColorConversion::rgbToHsv(...$this->rgb);

        return $this;
    }

    /**  @param null|array<string,int> $hsl */
    protected function setHsl(?array $hsl = null): self
    {
        $this->hsl = $hsl ?? ColorConversion::rgbToHsl(...$this->rgb);

        return $this;
    }

    /**  @param null|array<string,int> $cmyk */
    protected function setCmyk(?array $cmyk = null): self
    {
        $this->cmyk = $cmyk ?? ColorConversion::rgbToCmyk(...$this->rgb);

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
