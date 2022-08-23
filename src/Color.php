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

    /** @param  array<string,int>  $rgb */
    public function __construct(protected array $rgb)
    {
    }

    /* From methods */

    /**
     * @param  string  $name
     * @return Color
     *
     * @throws ColorConstructException
     */
    public static function fromName(string $name): Color
    {
        $colors = [
            'aliceblue' => 'f0f8ff',
            'antiquewhite' => 'faebd7',
            'aqua' => '00ffff',
            'aquamarine' => '7fffd4',
            'azure' => 'f0ffff',
            'beige' => 'f5f5dc',
            'bisque' => 'ffe4c4',
            'black' => '000000',
            'blanchedalmond' => 'ffebcd',
            'blue' => '0000ff',
            'blueviolet' => '8a2be2',
            'brown' => 'a52a2a',
            'burlywood' => 'deb887',
            'cadetblue' => '5f9ea0',
            'chartreuse' => '7fff00',
            'chocolate' => 'd2691e',
            'coral' => 'ff7f50',
            'cornflower' => '6495ed',
            'cornflowerblue' => '6495ed',
            'cornsilk' => 'fff8dc',
            'crimson' => 'dc143c',
            'cyan' => '00ffff',
            'darkblue' => '00008b',
            'darkcyan' => '008b8b',
            'darkgoldenrod' => 'b8860b',
            'darkgray' => 'a9a9a9',
            'darkgreen' => '006400',
            'darkgrey' => 'a9a9a9',
            'darkkhaki' => 'bdb76b',
            'darkmagenta' => '8b008b',
            'darkolivegreen' => '556b2f',
            'darkorange' => 'ff8c00',
            'darkorchid' => '9932cc',
            'darkred' => '8b0000',
            'darksalmon' => 'e9967a',
            'darkseagreen' => '8fbc8f',
            'darkslateblue' => '483d8b',
            'darkslategray' => '2f4f4f',
            'darkslategrey' => '2f4f4f',
            'darkturquoise' => '00ced1',
            'darkviolet' => '9400d3',
            'deeppink' => 'ff1493',
            'deepskyblue' => '00bfff',
            'dimgray' => '696969',
            'dimgrey' => '696969',
            'dodgerblue' => '1e90ff',
            'firebrick' => 'b22222',
            'floralwhite' => 'fffaf0',
            'forestgreen' => '228b22',
            'fuchsia' => 'ff00ff',
            'gainsboro' => 'dcdcdc',
            'ghostwhite' => 'f8f8ff',
            'gold' => 'ffd700',
            'goldenrod' => 'daa520',
            'gray' => '808080',
            'green' => '008000',
            'greenyellow' => 'adff2f',
            'grey' => '808080',
            'honeydew' => 'f0fff0',
            'hotpink' => 'ff69b4',
            'indianred' => 'cd5c5c',
            'indigo' => '4b0082',
            'ivory' => 'fffff0',
            'khaki' => 'f0e68c',
            'laserlemon' => 'ffff54',
            'lavender' => 'e6e6fa',
            'lavenderblush' => 'fff0f5',
            'lawngreen' => '7cfc00',
            'lemonchiffon' => 'fffacd',
            'lightblue' => 'add8e6',
            'lightcoral' => 'f08080',
            'lightcyan' => 'e0ffff',
            'lightgoldenrod' => 'fafad2',
            'lightgoldenrodyellow' => 'fafad2',
            'lightgray' => 'd3d3d3',
            'lightgreen' => '90ee90',
            'lightgrey' => 'd3d3d3',
            'lightpink' => 'ffb6c1',
            'lightsalmon' => 'ffa07a',
            'lightseagreen' => '20b2aa',
            'lightskyblue' => '87cefa',
            'lightslategray' => '778899',
            'lightslategrey' => '778899',
            'lightsteelblue' => 'b0c4de',
            'lightyellow' => 'ffffe0',
            'lime' => '00ff00',
            'limegreen' => '32cd32',
            'linen' => 'faf0e6',
            'magenta' => 'ff00ff',
            'maroon' => '800000',
            'maroon2' => '7f0000',
            'maroon3' => 'b03060',
            'mediumaquamarine' => '66cdaa',
            'mediumblue' => '0000cd',
            'mediumorchid' => 'ba55d3',
            'mediumpurple' => '9370db',
            'mediumseagreen' => '3cb371',
            'mediumslateblue' => '7b68ee',
            'mediumspringgreen' => '00fa9a',
            'mediumturquoise' => '48d1cc',
            'mediumvioletred' => 'c71585',
            'midnightblue' => '191970',
            'mintcream' => 'f5fffa',
            'mistyrose' => 'ffe4e1',
            'moccasin' => 'ffe4b5',
            'navajowhite' => 'ffdead',
            'navy' => '000080',
            'oldlace' => 'fdf5e6',
            'olive' => '808000',
            'olivedrab' => '6b8e23',
            'orange' => 'ffa500',
            'orangered' => 'ff4500',
            'orchid' => 'da70d6',
            'palegoldenrod' => 'eee8aa',
            'palegreen' => '98fb98',
            'paleturquoise' => 'afeeee',
            'palevioletred' => 'db7093',
            'papayawhip' => 'ffefd5',
            'peachpuff' => 'ffdab9',
            'peru' => 'cd853f',
            'pink' => 'ffc0cb',
            'plum' => 'dda0dd',
            'powderblue' => 'b0e0e6',
            'purple' => '800080',
            'purple2' => '7f007f',
            'purple3' => 'a020f0',
            'rebeccapurple' => '663399',
            'red' => 'ff0000',
            'rosybrown' => 'bc8f8f',
            'royalblue' => '4169e1',
            'saddlebrown' => '8b4513',
            'salmon' => 'fa8072',
            'sandybrown' => 'f4a460',
            'seagreen' => '2e8b57',
            'seashell' => 'fff5ee',
            'sienna' => 'a0522d',
            'silver' => 'c0c0c0',
            'skyblue' => '87ceeb',
            'slateblue' => '6a5acd',
            'slategray' => '708090',
            'slategrey' => '708090',
            'snow' => 'fffafa',
            'springgreen' => '00ff7f',
            'steelblue' => '4682b4',
            'tan' => 'd2b48c',
            'teal' => '008080',
            'thistle' => 'd8bfd8',
            'tomato' => 'ff6347',
            'turquoise' => '40e0d0',
            'violet' => 'ee82ee',
            'wheat' => 'f5deb3',
            'white' => 'ffffff',
            'whitesmoke' => 'f5f5f5',
            'yellow' => 'ffff00',
            'yellowgreen' => '9acd32',
        ];

        return isset($colors[$name]) ? self::fromHex($colors[$name]) : throw ColorConstructException::invalidColorName($name);
    }

    /**
     * @param  int  $r
     * @param  int  $g
     * @param  int  $b
     * @return Color
     */
    public static function fromRgb(int $r, int $g, int $b): Color
    {
        $rgb = self::sanitizeRgb($r, $g, $b);

        return new Color($rgb);
    }

    /**
     * @param  string  $hex
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
     * @param  int  $h
     * @param  int  $s
     * @param  int  $v
     * @return Color
     */
    public static function fromHsv(int $h, int $s, int $v): Color
    {
        $hsv = self::sanitizeHsv($h, $s, $v);
        $rgb = self::hsvToRgb(...$hsv);

        return (new Color($rgb))->setHsv($hsv);
    }

    /**
     * @param  int  $h
     * @param  int  $s
     * @param  int  $l
     * @return Color
     */
    public static function fromHsl(int $h, int $s, int $l): Color
    {
        $hsl = self::sanitizeHsl($h, $s, $l);
        $rgb = self::hslToRgb(...$hsl);

        return (new Color($rgb))->setHsl($hsl);
    }

    /**
     * @param  int  $c
     * @param  int  $m
     * @param  int  $y
     * @param  int  $k
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

    /**  @param  null|array<string,int>  $hsv */
    protected function setHsv(?array $hsv = null): self
    {
        $this->hsv = $hsv ?? self::rgbToHsv(...$this->rgb);

        return $this;
    }

    /**  @param  null|array<string,int>  $hsl */
    protected function setHsl(?array $hsl = null): self
    {
        $this->hsl = $hsl ?? self::rgbToHsl(...$this->rgb);

        return $this;
    }

    /**  @param  null|array<string,int>  $cmyk */
    protected function setCmyk(?array $cmyk = null): self
    {
        $this->cmyk = $cmyk ?? self::rgbToCmyk(...$this->rgb);

        return $this;
    }

    /* Export methods */

    public function hex(): string
    {
        if (! $this->hex) {
            $this->setHex();
        }

        return '#'.$this->hex;
    }

    /**
     * @return array<string,int>
     */
    public function hsv(): array
    {
        if (! $this->hsv) {
            $this->setHsv();
        }

        return $this->hsv;
    }

    /**
     * @return array<string,int>
     */
    public function hsl(): array
    {
        if (! $this->hsl) {
            $this->setHsl();
        }

        return $this->hsl;
    }

    /**
     * @return array<string,int>
     */
    public function cmyk(): array
    {
        if (! $this->cmyk) {
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
     * @param  array<string,int>  $rbg
     * @return string
     */
    public static function rgbToHex(array $rbg): string
    {
        return sprintf('%02x%02x%02x', ...array_values($rbg));
    }

    /**
     * @param  string  $hex
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

        return ['r' => (int) round($r), 'g' => (int) round($g), 'b' => (int) round($b)];
    }

    /**
     * @param  int  $r
     * @param  int  $b
     * @param  int  $g
     * @return array<string,int>
     */
    public static function rgbToHsv(int $r, int $b, int $g): array
    {
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $delta = $max - $min;

        if (! $delta) {
            $h = 0;
        } elseif ($r === $max) {
            $h = 60 * ((($g - $b) / $delta) % 6);
        } elseif ($g === $max) {
            $h = 60 * ((($b - $r) / $delta) + 2);
        } else {
            $h = 60 * ((($r - $g) / $delta) + 4);
        }

        $s = (bool) $max ? $delta / $max : 0;

        $v = $max;

        return ['h' => (int) floor($h), 's' => (int) floor($s), 'v' => (int) floor($v)];
    }

    /**
     * @param  int  $h
     * @param  int  $s
     * @param  int  $v
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
            $rgb[$i] = (int) round(($rgb[$i] + ($max - $rgb[$i]) * (1 - $s / 100)) * $factor);
        }

        return ['r' => (int) $rgb[0], 'g' => (int) $rgb[1], 'b' => (int) $rgb[2]];
    }

    /**
     * @param  int  $r
     * @param  int  $g
     * @param  int  $b
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

        return ['h' => (int) round($h), 's' => (int) round($s), 'l' => (int) round($l)];
    }

    /**
     * @param  int  $h
     * @param  int  $s
     * @param  int  $l
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
        $r = (int) round(($r + $m) * 255);
        $g = (int) round(($g + $m) * 255);
        $b = (int) round(($b + $m) * 255);

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

        return ['c' => intval($c), 'm' => intval($m), 'y' => intval($y), 'k' => intval($k)];
    }

    /**
     * @param  int  $c
     * @param  int  $m
     * @param  int  $y
     * @param  int  $k
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

        $r = (int) round($r * 255);
        $g = (int) round($g * 255);
        $b = (int) round($b * 255);

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
     * @param  int  $r
     * @param  int  $g
     * @param  int  $b
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
     * @param  string  $hex
     * @return string
     *
     * @throws ColorConstructException
     */
    protected static function sanitizeHex(string $hex): string
    {
        $hex = strtolower(str_replace('#', '', $hex));

        if (! preg_match('/^[a-fA-F0-9]+$/', $hex)) {
            throw ColorConstructException::invalidHexFormat($hex);
        }

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        } elseif (strlen($hex) !== 6) {
            throw ColorConstructException::invalidHexLenght($hex);
        }

        return $hex;
    }

    /**
     * @param  int  $h
     * @param  int  $s
     * @param  int  $v
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
     * @param  int  $h
     * @param  int  $s
     * @param  int  $l
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
     * @param  int  $c
     * @param  int  $m
     * @param  int  $y
     * @param  int  $k
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
        return 0.2126 * pow($this->rgb['r']/255, 2.2) +
            0.7152 * pow($this->rgb['g']/255, 2.2) +
            0.0722 * pow($this->rgb['b']/255, 2.2);
    }

    public static function contrastColors(Color $color1, Color $color2): float
    {
        $l1 = $color1->luminosity();
        $l2 = $color2->luminosity();

        if($l1 > $l2){
            return ($l1+0.05) / ($l2+0.05);
        }else{
            return ($l2+0.05) / ($l1+0.05);
        }
    }

    /**
     * @param Color $color
     * @return float
     */
    public function contrast(Color $color):float
    {
        return self::contrastColors($this,$color);
    }

}
