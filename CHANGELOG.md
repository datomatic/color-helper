# Changelog

All notable changes to `color-helper` will be documented in this file.

## v1.0.0 - 2026-11-21

### Breaking

- Requires PHP ^8.2.
- `ColorConstructException::invalidHexLenght()` renamed to `invalidHexLength()`; the exception now extends `InvalidArgumentException` and is no longer `final`.
- 4 and 8 digit hex values are read as colors with alpha instead of throwing.
- `luminosity()` is deprecated in favour of `luminance()` (WCAG formula).

### Added

- Alpha channel everywhere: `fromRgb/fromHsl/fromHsv/fromCmyk(..., $alpha)`, `#rgba` / `#rrggbbaa`, `alpha()`, `withAlpha()`, `isOpaque()`.
- `Color::parse()` for CSS colors: names, hex, `rgb()`/`rgba()`, `hsl()`/`hsla()` (comma or space syntax, `/ alpha`, percentages, `deg`).
- `rgbString()` and `hslString()`.
- `lighten()`, `darken()`, `saturate()`, `desaturate()`, `grayscale()`, `rotate()`, `invert()` (immutable).
- `meetsContrast()` with the `ContrastLevel` enum (AA/AAA, large text), `equals()`.
- Case-insensitive color names and `transparent`.

### Fixed

- `ColorConversion::rgbToHsv()` had the green and blue parameters swapped (positional calls returned the wrong hue).
- `bestContrastColor()` returned the last color instead of the one with the highest contrast.
- Negative and overflowing hues are normalized (`fromHsv(-30, ...)` returned red).
- Contrast ratios now match WCAG exactly.
- Channels are rounded the same way on every PHP version (PHP 8.4 changed `round()`).
- `averageColors([])` / `bestContrastColor([])` throw a clear exception; `mix()` validates the ratio.
- Removed the non-existent Laravel service provider from `composer.json`, which broke package discovery in Laravel apps.

### Other

- Strict types and array shapes, 295 tests, CI on PHP 8.2–8.6, README rewritten with an upgrade guide.

### What's Changed

* Bump actions/checkout from 3 to 4 by @dependabot[bot] in https://github.com/datomatic/color-helper/pull/12
* Bump stefanzweifel/git-auto-commit-action from 4 to 5 by @dependabot[bot] in https://github.com/datomatic/color-helper/pull/13

**Full Changelog**: https://github.com/datomatic/color-helper/compare/v0.1.0...v1.0.0

## v0.1.0 - 2026-11-20

First tagged release. Adds PHP 8.6 support to CI and upgrades dev tooling.

### What's Changed

* Bump dependabot/fetch-metadata from 1.3.3 to 1.3.4 by @dependabot[bot] in https://github.com/datomatic/color-helper/pull/2
* Bump dependabot/fetch-metadata from 1.3.4 to 1.3.5 by @dependabot[bot] in https://github.com/datomatic/color-helper/pull/3
* Bump dependabot/fetch-metadata from 1.3.5 to 1.3.6 by @dependabot[bot] in https://github.com/datomatic/color-helper/pull/6
* Bump dependabot/fetch-metadata from 1.3.6 to 1.4.0 by @dependabot[bot] in https://github.com/datomatic/color-helper/pull/8
* Bump dependabot/fetch-metadata from 1.4.0 to 1.5.1 by @dependabot[bot] in https://github.com/datomatic/color-helper/pull/9
* Bump ramsey/composer-install from 1 to 2 by @dependabot[bot] in https://github.com/datomatic/color-helper/pull/4
* Bump aglipanci/laravel-pint-action from 1.0.0 to 2.3.0 by @dependabot[bot] in https://github.com/datomatic/color-helper/pull/10
* Bump dependabot/fetch-metadata from 1.5.1 to 1.6.0 by @dependabot[bot] in https://github.com/datomatic/color-helper/pull/11

### New Contributors

* @dependabot[bot] made their first contribution in https://github.com/datomatic/color-helper/pull/2

**Full Changelog**: https://github.com/datomatic/color-helper/commits/v0.1.0
