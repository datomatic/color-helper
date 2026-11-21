![Color Helper-Dark](branding/dark.png#gh-dark-mode-only)![Color Helper-Light](branding/light.png#gh-light-mode-only)
# Color Helper

[![Latest Version on Packagist](https://img.shields.io/packagist/v/datomatic/color-helper.svg?style=for-the-badge)](https://packagist.org/packages/datomatic/color-helper)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/datomatic/color-helper/run-tests.yml?branch=main&label=tests&color=5FE8B3&style=for-the-badge)](https://github.com/datomatic/color-helper/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/datomatic/color-helper/fix-php-code-style-issues.yml?branch=main&label=code%20style&color=5FE8B3&style=for-the-badge)](https://github.com/datomatic/color-helper/actions/workflows/fix-php-code-style-issues.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/datomatic/color-helper.svg?style=for-the-badge)](https://packagist.org/packages/datomatic/color-helper)

Create, convert, manipulate and compare colors in PHP: hex, RGB, HSL, HSV, CMYK, CSS strings, alpha channel and WCAG contrast.

## Requirements

PHP 8.2 – 8.6, no other dependencies.

## Installation

```bash
composer require datomatic/color-helper
```

## Creating a color

```php
use Datomatic\Color\Color;

Color::fromHex('#1e90ff');          // also 'ff0', '#1e90ff80' (with alpha), '#f008'
Color::fromRgb(30, 144, 255);       // optional alpha: fromRgb(30, 144, 255, 0.5)
Color::fromHsl(210, 100, 56);
Color::fromHsv(210, 88, 100);
Color::fromCmyk(88, 44, 0, 0);
Color::fromName('DodgerBlue');      // CSS color names, case insensitive, and 'transparent'
Color::random();

// Any CSS color string
Color::parse('rgb(30 144 255 / 50%)');
Color::parse('rgba(30, 144, 255, 0.5)');
Color::parse('hsl(210deg 100% 56%)');
Color::parse('#1e90ff');
Color::parse('dodgerblue');
```

Hues are normalized (`-30` becomes `330`). Out of range values (RGB `0–255`, percentages `0–100`, alpha `0–1`) and invalid strings throw a `Datomatic\Color\Exceptions\ColorConstructException` (an `InvalidArgumentException`).

## Reading a color

```php
$color = Color::fromHex('#1e90ff');

$color->hex();        // '#1e90ff' ('#1e90ff80' when the color is translucent)
$color->hex(false);   // '1e90ff'
(string) $color;      // '#1e90ff'
$color->rgb();        // ['r' => 30, 'g' => 144, 'b' => 255]
$color->hsl();        // ['h' => 210, 's' => 100, 'l' => 56]
$color->hsv();        // ['h' => 210, 's' => 88, 'v' => 100]
$color->cmyk();       // ['c' => 88, 'm' => 44, 'y' => 0, 'k' => 0]
$color->alpha();      // 1.0
$color->rgbString();  // 'rgb(30, 144, 255)' or 'rgba(30, 144, 255, 0.5)'
$color->hslString();  // 'hsl(210, 100%, 56%)' or 'hsla(210, 100%, 56%, 0.5)'
```

HSL, HSV and CMYK values are integers, so converting back and forth may differ by one unit.

## Manipulating a color

`Color` is immutable: every method returns a new color and keeps the alpha channel.

```php
$color->lighten(20);      // HSL lightness + 20
$color->darken(20);
$color->saturate(20);     // HSL saturation + 20
$color->desaturate(20);
$color->grayscale();
$color->rotate(180);      // hue rotation in degrees, negative values allowed
$color->invert();
$color->withAlpha(0.5);

$color->mix(Color::fromHex('#ff0000'), 0.25);             // 25% red
Color::mixColors($first, $second, 0.5);
Color::averageColors([$first, $second, $third]);
```

Amounts go from `0` to `100`, the mix ratio from `0` (first color) to `1` (second color).

## Comparing colors

```php
use Datomatic\Color\Enums\ContrastLevel;

$text = Color::fromHex('#767676');
$background = Color::fromHex('#ffffff');

$text->luminance();                  // WCAG relative luminance, 0–1
$text->contrast($background);        // WCAG contrast ratio, 1–21 (4.54)
Color::contrastColors($text, $background);

$text->meetsContrast($background);                                  // AA, normal text (>= 4.5)
$text->meetsContrast($background, ContrastLevel::AAA);              // >= 7
$text->meetsContrast($background, ContrastLevel::AA, largeText: true); // >= 3

$background->bestContrastColor([Color::fromName('black'), Color::fromName('white')]); // black

$color->isLight();   // YIQ brightness above 130 (the limit is configurable: isLight(150))
$color->isDark();
$color->equals(Color::fromRgb(30, 144, 255)); // true
```

The contrast ignores the alpha channel.

## Conversions without a Color instance

```php
use Datomatic\Color\ColorConversion;

ColorConversion::rgbToHex(30, 144, 255);  // '1e90ff'
ColorConversion::hexToRgb('1e90ff');      // ['r' => 30, 'g' => 144, 'b' => 255]
ColorConversion::rgbToHsl(30, 144, 255);
ColorConversion::hslToRgb(210, 100, 56);
ColorConversion::rgbToHsv(30, 144, 255);
ColorConversion::hsvToRgb(210, 88, 100);
ColorConversion::rgbToCmyk(30, 144, 255);
ColorConversion::cmykToRgb(88, 44, 0, 0);
```

## Upgrading from 0.x

- PHP 8.2 is required.
- `ColorConstructException::invalidHexLenght()` was renamed to `invalidHexLength()`; the exception now extends `InvalidArgumentException` and is no longer `final`.
- 4 and 8 digit hex values are read as colors with alpha instead of throwing.
- `luminosity()` is deprecated in favour of `luminance()`, which uses the WCAG formula: contrast ratios are now exactly the WCAG ones (e.g. `#777` on white is 4.48, it was 4.43).
- `bestContrastColor()` returned the last color of the list: it now returns the one with the highest contrast.
- `ColorConversion::rgbToHsv()` had its `$g` and `$b` parameters swapped: calls with positional arguments now get the right hue.
- Negative hues are normalized (`fromHsv(-30, …)` was red, it is now `#ff0080`).
- `mix()` validates the ratio, and `averageColors()`/`bestContrastColor()` throw on an empty list instead of a PHP error.
- The non-existent Laravel service provider was removed from `composer.json`.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/datomatic/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Alberto Peripolli](https://github.com/datomatic)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
