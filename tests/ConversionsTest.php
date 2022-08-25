<?php

use Datomatic\Color\Color;
use Datomatic\Color\ColorConversion;

it('can convert rgb to hex with #', function (int $r, int $g, int $b, string $hex) {
    $color = Color::fromRgb($r, $g, $b);
    expect($color)->toBeInstanceOf(Color::class);
    expect($color->hex())->toBe($hex);
})->with([
    [0, 158, 204, '#009ecc'],
    [244, 231, 15, '#f4e70f'],
    [0, 0, 0, '#000000'],
    [255, 255, 255, '#ffffff'],
]);

it('can convert rgb to hex withouth #', function (int $r, int $g, int $b, string $hex) {
    $color = Color::fromRgb($r, $g, $b);
    expect($color->hex(false))->toBe($hex);
    expect(ColorConversion::rgbToHex($r, $g, $b))->toBe($hex);
})->with([
    [0, 158, 204, '009ecc'],
    [244, 231, 15, 'f4e70f'],
    [0, 0, 0, '000000'],
    [255, 255, 255, 'ffffff'],
]);

it('can convert hex to rgb', function (string $hex, int $r, int $g, int $b) {
    $color = Color::fromHex($hex);
    expect($color->rgb())->toBe(['r' => $r, 'g' => $g, 'b' => $b]);
})->with([
    ['009ecc', 0, 158, 204],
    ['#f4e70f', 244, 231, 15],
    ['#000', 0, 0, 0],
    ['FFF', 255, 255, 255],
]);


it('can convert rgb to hsv', function (array $rgb, array $hsv) {
    $color = Color::fromRgb(...$rgb);
    expect($color->hsv())->toBe($hsv);
    expect(ColorConversion::rgbToHsv(...$rgb))->toBe($hsv);
})->with([
    [['r' => 66, 'g' => 135, 'b' => 245], ['h' => 217, 's' => 73, 'v' => 96]],
    [['r' => 143, 'g' => 19, 'b' => 62], ['h' => 339, 's' => 87, 'v' => 56]],
    [['r' => 114, 'g' => 207, 'b' => 131], ['h' => 131, 's' => 45, 'v' => 81]],
    [['r' => 0, 'g' => 0, 'b' => 0], ['h' => 0, 's' => 0, 'v' => 0]],
    [['r' => 255, 'g' => 255, 'b' => 255], ['h' => 0, 's' => 0, 'v' => 100]],
]);

it('can convert hsv to rgb', function (array $hsv, array $rgb) {
    $color = Color::fromHsv(...$hsv);
    expect($color->rgb())->toBe($rgb);
    expect(ColorConversion::hsvToRgb(...$hsv))->toBe($rgb);
})->with([
    [['h' => 217, 's' => 73, 'v' => 96], ['r' => 66, 'g' => 135, 'b' => 245]],
    [['h' => 339, 's' => 87, 'v' => 56], ['r' => 143, 'g' => 19, 'b' => 62]],
    [['h' => 131, 's' => 45, 'v' => 81], ['r' => 114, 'g' => 207, 'b' => 131]],
    [['h' => 0, 's' => 0, 'v' => 0], ['r' => 0, 'g' => 0, 'b' => 0]],
    [['h' => 0, 's' => 0, 'v' => 100], ['r' => 255, 'g' => 255, 'b' => 255]],
]);


it('can convert rgb to hsl', function (array $rgb, array $hsl) {
    $color = Color::fromRgb(...$rgb);
    expect($color->hsl())->toBe($hsl);
    expect(ColorConversion::rgbToHsl(...$rgb))->toBe($hsl);
})->with([
    [['r' => 66, 'g' => 135, 'b' => 245], ['h' => 217, 's' => 90, 'l' => 61]],
    [['r' => 143, 'g' => 19, 'b' => 62], ['h' => 339, 's' => 77, 'l' => 32]],
    [['r' => 114, 'g' => 207, 'b' => 131], ['h' => 131, 's' => 49, 'l' => 63]],
    [['r' => 0, 'g' => 0, 'b' => 0], ['h' => 0, 's' => 0, 'l' => 0]],
    [['r' => 255, 'g' => 255, 'b' => 255], ['h' => 0, 's' => 0, 'l' => 100]],
]);


it('can convert hsl to rgb', function (array $hsl, array $rgb) {
    $color = Color::fromHsl(...$hsl);
    expect($color->rgb())->toBe($rgb);
    expect(ColorConversion::hslToRgb(...$hsl))->toBe($rgb);
})->with([
    [['h' => 217, 's' => 90, 'l' => 61], ['r' => 66, 'g' => 135, 'b' => 245]],
    [['h' => 339, 's' => 77, 'l' => 32], ['r' => 144, 'g' => 19, 'b' => 63]],
    [['h' => 290, 's' => 77, 'l' => 63], ['r' => 209, 'g' => 88, 'b' => 233]],
    [['h' => 131, 's' => 49, 'l' => 63], ['r' => 114, 'g' => 207, 'b' => 131]],
    [['h' => 0, 's' => 0, 'l' => 0], ['r' => 0, 'g' => 0, 'b' => 0]],
    [['h' => 0, 's' => 0, 'l' => 100], ['r' => 255, 'g' => 255, 'b' => 255]],
]);


it('can convert rgb to cmyk', function (array $rgb, array $cmyk) {
    $color = Color::fromRgb(...$rgb);
    expect($color->cmyk())->toBe($cmyk);
    expect(ColorConversion::rgbToCmyk(...$rgb))->toBe($cmyk);
})->with([
    [['r' => 66, 'g' => 135, 'b' => 245], ['c' => 73, 'm' => 45, 'y' => 0, 'k' => 4]],
    [['r' => 143, 'g' => 19, 'b' => 62], ['c' => 0, 'm' => 87, 'y' => 57, 'k' => 44]],
    [['r' => 114, 'g' => 207, 'b' => 131], ['c' => 45, 'm' => 0, 'y' => 37, 'k' => 19]],
    [['r' => 0, 'g' => 0, 'b' => 0], ['c' => 0, 'm' => 0, 'y' => 0, 'k' => 100]],
    [['r' => 255, 'g' => 255, 'b' => 255], ['c' => 0, 'm' => 0, 'y' => 0, 'k' => 0]],
]);


it('can convert cmyk to rgb', function (array $cmyk, array $rgb) {
    $color = Color::fromCmyk(...$cmyk);
    expect($color->rgb())->toBe($rgb);
    expect(ColorConversion::cmykToRgb(...$cmyk))->toBe($rgb);
})->with([
    [['c' => 73, 'm' => 45, 'y' => 0, 'k' => 4], ['r' => 66, 'g' => 135, 'b' => 245]],
    [['c' => 0, 'm' => 87, 'y' => 57, 'k' => 44], ['r' => 143, 'g' => 19, 'b' => 61]],
    [['c' => 45, 'm' => 0, 'y' => 37, 'k' => 19], ['r' => 114, 'g' => 207, 'b' => 130]],
    [['c' => 0, 'm' => 0, 'y' => 0, 'k' => 100], ['r' => 0, 'g' => 0, 'b' => 0]],
    [['c' => 0, 'm' => 0, 'y' => 0, 'k' => 0], ['r' => 255, 'g' => 255, 'b' => 255]],
]);
