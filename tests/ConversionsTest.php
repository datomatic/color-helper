<?php

use Datomatic\Color\Color;
use Datomatic\Color\ColorConversion;

it('can convert rgb to hex with #', function (int $r, int $g, int $b, string $hex) {
    $enum = Color::fromRgb($r, $g, $b);
    expect($enum)->toBeInstanceOf(Color::class);
    expect($enum->hex())->toBe($hex);
})->with([
    [0, 158, 204, '#009ecc'],
    [244, 231, 15, '#f4e70f'],
    [0, 0, 0, '#000000'],
    [255, 255, 255, '#ffffff'],
]);

it('can convert rgb to hex withouth #', function (int $r, int $g, int $b, string $hex) {
    $enum = Color::fromRgb($r, $g, $b);
    expect($enum->hex(false))->toBe($hex);
    expect(ColorConversion::rgbToHex($r, $g, $b))->toBe($hex);
})->with([
    [0, 158, 204, '009ecc'],
    [244, 231, 15, 'f4e70f'],
    [0, 0, 0, '000000'],
    [255, 255, 255, 'ffffff'],
]);

it('can convert hex to rgb', function (string $hex, int $r, int $g, int $b) {
    $enum = Color::fromHex($hex);
    expect($enum->rgb())->toBe(['r' => $r, 'g' => $g, 'b' => $b]);
})->with([
    ['009ecc', 0, 158, 204],
    ['#f4e70f', 244, 231, 15],
    ['#000', 0, 0, 0],
    ['fff', 255, 255, 255],
]);


it('can convert rgb to hsv', function (array $rgb, array $hsv) {
    $enum = Color::fromRgb(...$rgb);
    expect($enum->hsv())->toBe($hsv);
    expect(ColorConversion::rgbToHsv(...$rgb))->toBe($hsv);
})->with([
    [['r' => 66, 'g' => 135, 'b' => 245], ['h' => 217, 's' => 73, 'v' => 96]],
    [['r' => 143, 'g' => 19, 'b' => 62], ['h' => 339, 's' => 87, 'v' => 56]],
    [['r' => 114, 'g' => 207, 'b' => 131], ['h' => 131, 's' => 45, 'v' => 81]],
    [['r' => 0, 'g' => 0, 'b' => 0], ['h' => 0, 's' => 0, 'v' => 0]],
    [['r' => 255, 'g' => 255, 'b' => 255], ['h' => 0, 's' => 0, 'v' => 100]],
]);


it('can convert rgb to hsl', function (array $rgb, array $hsl) {
    $enum = Color::fromRgb(...$rgb);
    expect($enum->hsl())->toBe($hsl);
    expect(ColorConversion::rgbToHsl(...$rgb))->toBe($hsl);
})->with([
    [['r' => 66, 'g' => 135, 'b' => 245], ['h' => 217, 's' => 90, 'l' => 61]],
    [['r' => 143, 'g' => 19, 'b' => 62], ['h' => 339, 's' => 77, 'l' => 32]],
    [['r' => 114, 'g' => 207, 'b' => 131], ['h' => 131, 's' => 49, 'l' => 63]],
    [['r' => 0, 'g' => 0, 'b' => 0], ['h' => 0, 's' => 0, 'l' => 0]],
    [['r' => 255, 'g' => 255, 'b' => 255], ['h' => 0, 's' => 0, 'l' => 100]],
]);


it('can convert rgb to cmyk', function (array $rgb, array $cmyk) {
    $enum = Color::fromRgb(...$rgb);
    expect($enum->cmyk())->toBe($cmyk);
    expect(ColorConversion::rgbToCmyk(...$rgb))->toBe($cmyk);
})->with([
    [['r' => 66, 'g' => 135, 'b' => 245], ['c' => 73, 'm' => 45, 'y' => 0, 'k' => 4]],
    [['r' => 143, 'g' => 19, 'b' => 62], ['c' => 0, 'm' => 87, 'y' => 57, 'k' => 44]],
    [['r' => 114, 'g' => 207, 'b' => 131], ['c' => 45, 'm' => 0, 'y' => 37, 'k' => 19]],
    [['r' => 0, 'g' => 0, 'b' => 0], ['c' => 0, 'm' => 0, 'y' => 0, 'k' => 100]],
    [['r' => 255, 'g' => 255, 'b' => 255], ['c' => 0, 'm' => 0, 'y' => 0, 'k' => 0]],
]);
