<?php

declare(strict_types=1);

namespace Datomatic\Color;

use Datomatic\Color\Enums\ContrastLevel;
use Datomatic\Color\Exceptions\ColorConstructException;
use Stringable;

class Color implements Stringable
{
    /** @var array{r: int, g: int, b: int} */
    protected array $rgb;

    protected float $alpha;

    protected ?string $hex = null;

    /** @var array{h: int, s: int, l: int}|null */
    protected ?array $hsl = null;

    /** @var array{h: int, s: int, v: int}|null */
    protected ?array $hsv = null;

    /** @var array{c: int, m: int, y: int, k: int}|null */
    protected ?array $cmyk = null;

    /**
     * @throws ColorConstructException
     */
    public function __construct(int $r, int $g, int $b, float $alpha = 1.0)
    {
        $this->rgb = self::sanitizeRgb($r, $g, $b);
        $this->alpha = self::sanitizeAlpha($alpha);
    }

    /**
     * @throws ColorConstructException
     */
    public static function fromName(string $name): self
    {
        $normalizedName = strtolower(trim($name));

        if ($normalizedName === 'transparent') {
            return new self(0, 0, 0, 0.0);
        }

        if (isset(ColorNames::COLORS[$normalizedName])) {
            return self::fromHex(ColorNames::COLORS[$normalizedName]);
        }

        throw ColorConstructException::invalidColorName($name);
    }

    /**
     * @throws ColorConstructException
     */
    public static function fromRgb(int $r, int $g, int $b, float $alpha = 1.0): self
    {
        return new self($r, $g, $b, $alpha);
    }

    /**
     * Accepts 3, 4, 6 or 8 digits, with or without `#`: the 4th and 8th digits are the alpha channel.
     *
     * @throws ColorConstructException
     */
    public static function fromHex(string $hex): self
    {
        $sanitizedHex = self::sanitizeHex($hex);
        $rgbHex = substr($sanitizedHex, 0, 6);
        $alpha = strlen($sanitizedHex) === 8 ? round(hexdec(substr($sanitizedHex, 6, 2)) / 255, 3) : 1.0;

        $color = self::fromRgbArray(ColorConversion::hexToRgb($rgbHex), $alpha);
        $color->hex = $rgbHex;

        return $color;
    }

    /**
     * @throws ColorConstructException
     */
    public static function fromHsv(int $h, int $s, int $v, float $alpha = 1.0): self
    {
        $hsv = self::sanitizeHsv($h, $s, $v);

        $color = self::fromRgbArray(ColorConversion::hsvToRgb(...$hsv), $alpha);
        $color->hsv = $hsv;

        return $color;
    }

    /**
     * @throws ColorConstructException
     */
    public static function fromHsl(int $h, int $s, int $l, float $alpha = 1.0): self
    {
        $hsl = self::sanitizeHsl($h, $s, $l);

        $color = self::fromRgbArray(ColorConversion::hslToRgb(...$hsl), $alpha);
        $color->hsl = $hsl;

        return $color;
    }

    /**
     * @throws ColorConstructException
     */
    public static function fromCmyk(int $c, int $m, int $y, int $k, float $alpha = 1.0): self
    {
        $cmyk = self::sanitizeCmyk($c, $m, $y, $k);

        $color = self::fromRgbArray(ColorConversion::cmykToRgb(...$cmyk), $alpha);
        $color->cmyk = $cmyk;

        return $color;
    }

    /**
     * Parses a CSS color: a name, a hex value, `rgb()`/`rgba()` or `hsl()`/`hsla()`
     * (comma or space separated, with an optional `/ alpha`).
     *
     * @throws ColorConstructException
     */
    public static function parse(string $color): self
    {
        $value = strtolower(trim($color));

        if ($value === 'transparent' || isset(ColorNames::COLORS[$value])) {
            return self::fromName($value);
        }

        if (preg_match('/^(rgba?|hsla?)\((.*)\)$/', $value, $matches)) {
            return self::fromCssFunction($color, $matches[1], $matches[2]);
        }

        if (preg_match('/^#?[0-9a-f]+$/', $value)) {
            return self::fromHex($value);
        }

        throw ColorConstructException::invalidColorString($color);
    }

    public static function random(): self
    {
        return new self(random_int(0, 255), random_int(0, 255), random_int(0, 255));
    }

    /**
     * @return array{r: int, g: int, b: int}
     */
    public function rgb(): array
    {
        return $this->rgb;
    }

    public function alpha(): float
    {
        return $this->alpha;
    }

    /**
     * Returns 8 digits when the color is not fully opaque.
     */
    public function hex(bool $hash = true): string
    {
        $this->hex ??= ColorConversion::rgbToHex(...$this->rgb);

        $alphaHex = $this->isOpaque() ? '' : sprintf('%02x', (int) round($this->alpha * 255));

        return ($hash ? '#' : '').$this->hex.$alphaHex;
    }

    /**
     * @return array{h: int, s: int, v: int}
     */
    public function hsv(): array
    {
        return $this->hsv ??= ColorConversion::rgbToHsv(...$this->rgb);
    }

    /**
     * @return array{h: int, s: int, l: int}
     */
    public function hsl(): array
    {
        return $this->hsl ??= ColorConversion::rgbToHsl(...$this->rgb);
    }

    /**
     * @return array{c: int, m: int, y: int, k: int}
     */
    public function cmyk(): array
    {
        return $this->cmyk ??= ColorConversion::rgbToCmyk(...$this->rgb);
    }

    public function rgbString(): string
    {
        ['r' => $r, 'g' => $g, 'b' => $b] = $this->rgb;

        return $this->isOpaque()
            ? "rgb({$r}, {$g}, {$b})"
            : "rgba({$r}, {$g}, {$b}, {$this->formattedAlpha()})";
    }

    public function hslString(): string
    {
        ['h' => $h, 's' => $s, 'l' => $l] = $this->hsl();

        return $this->isOpaque()
            ? "hsl({$h}, {$s}%, {$l}%)"
            : "hsla({$h}, {$s}%, {$l}%, {$this->formattedAlpha()})";
    }

    public function __toString(): string
    {
        return $this->hex();
    }

    public function isOpaque(): bool
    {
        return $this->alpha >= 1.0;
    }

    public function equals(self $color): bool
    {
        return $this->rgb === $color->rgb()
            && abs($this->alpha - $color->alpha()) < 0.001;
    }

    /**
     * @throws ColorConstructException
     */
    public function withAlpha(float $alpha): self
    {
        return self::fromRgbArray($this->rgb, $alpha);
    }

    /**
     * @throws ColorConstructException
     */
    public function lighten(int $amount): self
    {
        return $this->adjustHsl('l', self::sanitizeAmount($amount));
    }

    /**
     * @throws ColorConstructException
     */
    public function darken(int $amount): self
    {
        return $this->adjustHsl('l', -self::sanitizeAmount($amount));
    }

    /**
     * @throws ColorConstructException
     */
    public function saturate(int $amount): self
    {
        return $this->adjustHsl('s', self::sanitizeAmount($amount));
    }

    /**
     * @throws ColorConstructException
     */
    public function desaturate(int $amount): self
    {
        return $this->adjustHsl('s', -self::sanitizeAmount($amount));
    }

    public function grayscale(): self
    {
        return $this->desaturate(100);
    }

    public function rotate(int $degrees): self
    {
        ['h' => $h, 's' => $s, 'l' => $l] = $this->hsl();

        return self::fromHsl($h + $degrees, $s, $l, $this->alpha);
    }

    public function invert(): self
    {
        ['r' => $r, 'g' => $g, 'b' => $b] = $this->rgb;

        return new self(255 - $r, 255 - $g, 255 - $b, $this->alpha);
    }

    /**
     * YIQ perceived brightness, from 0 to 255.
     */
    public function isLight(int $limit = 130): bool
    {
        ['r' => $r, 'g' => $g, 'b' => $b] = $this->rgb;

        return ($r * 299 + $g * 587 + $b * 114) / 1000 > $limit;
    }

    public function isDark(int $limit = 130): bool
    {
        return ! $this->isLight($limit);
    }

    /**
     * WCAG 2.x relative luminance, from 0 (black) to 1 (white). Alpha is ignored.
     */
    public function luminance(): float
    {
        $channel = static function (int $value): float {
            $value /= 255;

            return $value <= 0.04045 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
        };

        return 0.2126 * $channel($this->rgb['r'])
            + 0.7152 * $channel($this->rgb['g'])
            + 0.0722 * $channel($this->rgb['b']);
    }

    /**
     * @deprecated Use luminance() instead.
     */
    public function luminosity(): float
    {
        return $this->luminance();
    }

    /**
     * WCAG 2.x contrast ratio, from 1 to 21.
     */
    public static function contrastColors(self $color1, self $color2): float
    {
        $lighter = max($color1->luminance(), $color2->luminance());
        $darker = min($color1->luminance(), $color2->luminance());

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    public function contrast(self $color): float
    {
        return self::contrastColors($this, $color);
    }

    public function meetsContrast(self $color, ContrastLevel $level = ContrastLevel::AA, bool $largeText = false): bool
    {
        return $this->contrast($color) >= $level->minimumRatio($largeText);
    }

    /**
     * @param  array<self>  $colors
     *
     * @throws ColorConstructException
     */
    public function bestContrastColor(array $colors): self
    {
        if ($colors === []) {
            throw ColorConstructException::emptyColorList();
        }

        $bestColor = null;
        $bestContrast = 0.0;

        foreach ($colors as $color) {
            $contrast = $this->contrast($color);

            if ($contrast > $bestContrast) {
                $bestColor = $color;
                $bestContrast = $contrast;
            }
        }

        return $bestColor ?? reset($colors);
    }

    /**
     * @param  float  $ratio  0 returns the first color, 1 the second one
     *
     * @throws ColorConstructException
     */
    public static function mixColors(self $color1, self $color2, float $ratio = 0.5): self
    {
        if ($ratio < 0 || $ratio > 1) {
            throw ColorConstructException::invalidFloatValue('ratio', 0, 1);
        }

        $rgb1 = $color1->rgb();
        $rgb2 = $color2->rgb();
        $channel = static fn (int|float $first, int|float $second): float => $first * (1 - $ratio) + $second * $ratio;

        return new self(
            (int) round($channel($rgb1['r'], $rgb2['r'])),
            (int) round($channel($rgb1['g'], $rgb2['g'])),
            (int) round($channel($rgb1['b'], $rgb2['b'])),
            round($channel($color1->alpha(), $color2->alpha()), 3),
        );
    }

    /**
     * @throws ColorConstructException
     */
    public function mix(self $color, float $ratio = 0.5): self
    {
        return self::mixColors($this, $color, $ratio);
    }

    /**
     * @param  array<self>  $colors
     *
     * @throws ColorConstructException
     */
    public static function averageColors(array $colors): self
    {
        if ($colors === []) {
            throw ColorConstructException::emptyColorList();
        }

        $r = $g = $b = 0;
        $alpha = 0.0;

        foreach ($colors as $color) {
            $rgb = $color->rgb();
            $r += $rgb['r'];
            $g += $rgb['g'];
            $b += $rgb['b'];
            $alpha += $color->alpha();
        }

        $count = count($colors);

        return new self(
            (int) round($r / $count),
            (int) round($g / $count),
            (int) round($b / $count),
            round($alpha / $count, 3),
        );
    }

    public function clone(): self
    {
        return self::fromRgbArray($this->rgb, $this->alpha);
    }

    /**
     * @param  array{r: int, g: int, b: int}  $rgb
     *
     * @throws ColorConstructException
     */
    private static function fromRgbArray(array $rgb, float $alpha): self
    {
        return new self($rgb['r'], $rgb['g'], $rgb['b'], $alpha);
    }

    /**
     * @param  's'|'l'  $channel
     */
    private function adjustHsl(string $channel, int $amount): self
    {
        $hsl = $this->hsl();
        $hsl[$channel] = max(0, min(100, $hsl[$channel] + $amount));

        return self::fromHsl($hsl['h'], $hsl['s'], $hsl['l'], $this->alpha);
    }

    private function formattedAlpha(): string
    {
        return (string) round($this->alpha, 3);
    }

    /**
     * @throws ColorConstructException
     */
    private static function fromCssFunction(string $color, string $function, string $arguments): self
    {
        $parts = preg_split('/[\s,\/]+/', trim($arguments), -1, PREG_SPLIT_NO_EMPTY);

        if ($parts === false || count($parts) < 3 || count($parts) > 4) {
            throw ColorConstructException::invalidColorString($color);
        }

        $alpha = isset($parts[3]) ? self::parseCssNumber($color, $parts[3], 1) : 1.0;

        if (str_starts_with($function, 'rgb')) {
            return new self(
                (int) round(self::parseCssNumber($color, $parts[0], 255)),
                (int) round(self::parseCssNumber($color, $parts[1], 255)),
                (int) round(self::parseCssNumber($color, $parts[2], 255)),
                $alpha,
            );
        }

        return self::fromHsl(
            (int) round(self::parseCssNumber($color, str_replace('deg', '', $parts[0]), 360)),
            (int) round(self::parseCssNumber($color, $parts[1], 100)),
            (int) round(self::parseCssNumber($color, $parts[2], 100)),
            $alpha,
        );
    }

    /**
     * @param  int  $percentBase  the value that 100% maps to
     *
     * @throws ColorConstructException
     */
    private static function parseCssNumber(string $color, string $value, int $percentBase): float
    {
        $isPercentage = str_ends_with($value, '%');
        $number = $isPercentage ? substr($value, 0, -1) : $value;

        if (! is_numeric($number)) {
            throw ColorConstructException::invalidColorString($color);
        }

        return $isPercentage ? (float) $number * $percentBase / 100 : (float) $number;
    }

    /**
     * @throws ColorConstructException
     */
    private static function checkRange(string $param, int $number, int $min, int $max): void
    {
        if ($number < $min || $number > $max) {
            throw ColorConstructException::invalidIntegerValue($param, $min, $max);
        }
    }

    /**
     * @throws ColorConstructException
     */
    private static function sanitizeAmount(int $amount): int
    {
        self::checkRange('amount', $amount, 0, 100);

        return $amount;
    }

    /**
     * @throws ColorConstructException
     */
    private static function sanitizeAlpha(float $alpha): float
    {
        if ($alpha < 0 || $alpha > 1) {
            throw ColorConstructException::invalidFloatValue('alpha', 0, 1);
        }

        return $alpha;
    }

    /**
     * @return array{r: int, g: int, b: int}
     *
     * @throws ColorConstructException
     */
    protected static function sanitizeRgb(int $r, int $g, int $b): array
    {
        foreach (['r' => $r, 'g' => $g, 'b' => $b] as $param => $value) {
            self::checkRange($param, $value, 0, 255);
        }

        return ['r' => $r, 'g' => $g, 'b' => $b];
    }

    /**
     * Returns 6 or 8 lowercase digits, without `#`.
     *
     * @throws ColorConstructException
     */
    protected static function sanitizeHex(string $hex): string
    {
        $hex = strtolower(ltrim(trim($hex), '#'));

        if (! preg_match('/^[a-f0-9]+$/', $hex)) {
            throw ColorConstructException::invalidHexFormat($hex);
        }

        return match (strlen($hex)) {
            3, 4 => implode('', array_map(static fn (string $digit): string => $digit.$digit, str_split($hex))),
            6, 8 => $hex,
            default => throw ColorConstructException::invalidHexLength($hex),
        };
    }

    /**
     * @return array{h: int, s: int, v: int}
     *
     * @throws ColorConstructException
     */
    protected static function sanitizeHsv(int $h, int $s, int $v): array
    {
        foreach (['s' => $s, 'v' => $v] as $param => $value) {
            self::checkRange($param, $value, 0, 100);
        }

        return ['h' => ColorConversion::normalizeHue($h), 's' => $s, 'v' => $v];
    }

    /**
     * @return array{h: int, s: int, l: int}
     *
     * @throws ColorConstructException
     */
    protected static function sanitizeHsl(int $h, int $s, int $l): array
    {
        foreach (['s' => $s, 'l' => $l] as $param => $value) {
            self::checkRange($param, $value, 0, 100);
        }

        return ['h' => ColorConversion::normalizeHue($h), 's' => $s, 'l' => $l];
    }

    /**
     * @return array{c: int, m: int, y: int, k: int}
     *
     * @throws ColorConstructException
     */
    protected static function sanitizeCmyk(int $c, int $m, int $y, int $k): array
    {
        foreach (['c' => $c, 'm' => $m, 'y' => $y, 'k' => $k] as $param => $value) {
            self::checkRange($param, $value, 0, 100);
        }

        return ['c' => $c, 'm' => $m, 'y' => $y, 'k' => $k];
    }
}
