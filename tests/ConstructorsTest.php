<?php

use Datomatic\Color\Color;
use Datomatic\Color\Exceptions\ColorConstructException;

it('can instantiate from string', function (string $string, string $hex) {
    $enum = Color::fromName($string);
    expect($enum)->toBeInstanceOf(Color::class);
    expect($enum->hex())->toBe($hex);
})->with([
    ['aliceblue', '#f0f8ff'],
    ['antiquewhite', '#faebd7'],
    ['aqua', '#00ffff'],
    ['aquamarine', '#7fffd4'],
    ['azure', '#f0ffff'],
    ['beige', '#f5f5dc'],
    ['bisque', '#ffe4c4'],
    ['black', '#000000'],
    ['blanchedalmond', '#ffebcd'],
    ['blue', '#0000ff'],
    ['blueviolet', '#8a2be2'],
    ['brown', '#a52a2a'],
    ['burlywood', '#deb887'],
    ['cadetblue', '#5f9ea0'],
    ['chartreuse', '#7fff00'],
    ['chocolate', '#d2691e'],
    ['coral', '#ff7f50'],
    ['cornflower', '#6495ed'],
    ['cornflowerblue', '#6495ed'],
    ['cornsilk', '#fff8dc'],
    ['crimson', '#dc143c'],
    ['cyan', '#00ffff'],
    ['darkblue', '#00008b'],
    ['darkcyan', '#008b8b'],
    ['darkgoldenrod', '#b8860b'],
    ['darkgray', '#a9a9a9'],
    ['darkgreen', '#006400'],
    ['darkgrey', '#a9a9a9'],
    ['darkkhaki', '#bdb76b'],
    ['darkmagenta', '#8b008b'],
    ['darkolivegreen', '#556b2f'],
    ['darkorange', '#ff8c00'],
    ['darkorchid', '#9932cc'],
    ['darkred', '#8b0000'],
    ['darksalmon', '#e9967a'],
    ['darkseagreen', '#8fbc8f'],
    ['darkslateblue', '#483d8b'],
    ['darkslategray', '#2f4f4f'],
    ['darkslategrey', '#2f4f4f'],
    ['darkturquoise', '#00ced1'],
    ['darkviolet', '#9400d3'],
    ['deeppink', '#ff1493'],
    ['deepskyblue', '#00bfff'],
    ['dimgray', '#696969'],
    ['dimgrey', '#696969'],
    ['dodgerblue', '#1e90ff'],
    ['firebrick', '#b22222'],
    ['floralwhite', '#fffaf0'],
    ['forestgreen', '#228b22'],
    ['fuchsia', '#ff00ff'],
    ['gainsboro', '#dcdcdc'],
    ['ghostwhite', '#f8f8ff'],
    ['gold', '#ffd700'],
    ['goldenrod', '#daa520'],
    ['gray', '#808080'],
    ['green', '#008000'],
    ['greenyellow', '#adff2f'],
    ['grey', '#808080'],
    ['honeydew', '#f0fff0'],
    ['hotpink', '#ff69b4'],
    ['indianred', '#cd5c5c'],
    ['indigo', '#4b0082'],
    ['ivory', '#fffff0'],
    ['khaki', '#f0e68c'],
    ['laserlemon', '#ffff54'],
    ['lavender', '#e6e6fa'],
    ['lavenderblush', '#fff0f5'],
    ['lawngreen', '#7cfc00'],
    ['lemonchiffon', '#fffacd'],
    ['lightblue', '#add8e6'],
    ['lightcoral', '#f08080'],
    ['lightcyan', '#e0ffff'],
    ['lightgoldenrod', '#fafad2'],
    ['lightgoldenrodyellow', '#fafad2'],
    ['lightgray', '#d3d3d3'],
    ['lightgreen', '#90ee90'],
    ['lightgrey', '#d3d3d3'],
    ['lightpink', '#ffb6c1'],
    ['lightsalmon', '#ffa07a'],
    ['lightseagreen', '#20b2aa'],
    ['lightskyblue', '#87cefa'],
    ['lightslategray', '#778899'],
    ['lightslategrey', '#778899'],
    ['lightsteelblue', '#b0c4de'],
    ['lightyellow', '#ffffe0'],
    ['lime', '#00ff00'],
    ['limegreen', '#32cd32'],
    ['linen', '#faf0e6'],
    ['magenta', '#ff00ff'],
    ['maroon', '#800000'],
    ['maroon2', '#7f0000'],
    ['maroon3', '#b03060'],
    ['mediumaquamarine', '#66cdaa'],
    ['mediumblue', '#0000cd'],
    ['mediumorchid', '#ba55d3'],
    ['mediumpurple', '#9370db'],
    ['mediumseagreen', '#3cb371'],
    ['mediumslateblue', '#7b68ee'],
    ['mediumspringgreen', '#00fa9a'],
    ['mediumturquoise', '#48d1cc'],
    ['mediumvioletred', '#c71585'],
    ['midnightblue', '#191970'],
    ['mintcream', '#f5fffa'],
    ['mistyrose', '#ffe4e1'],
    ['moccasin', '#ffe4b5'],
    ['navajowhite', '#ffdead'],
    ['navy', '#000080'],
    ['oldlace', '#fdf5e6'],
    ['olive', '#808000'],
    ['olivedrab', '#6b8e23'],
    ['orange', '#ffa500'],
    ['orangered', '#ff4500'],
    ['orchid', '#da70d6'],
    ['palegoldenrod', '#eee8aa'],
    ['palegreen', '#98fb98'],
    ['paleturquoise', '#afeeee'],
    ['palevioletred', '#db7093'],
    ['papayawhip', '#ffefd5'],
    ['peachpuff', '#ffdab9'],
    ['peru', '#cd853f'],
    ['pink', '#ffc0cb'],
    ['plum', '#dda0dd'],
    ['powderblue', '#b0e0e6'],
    ['purple', '#800080'],
    ['purple2', '#7f007f'],
    ['purple3', '#a020f0'],
    ['rebeccapurple', '#663399'],
    ['red', '#ff0000'],
    ['rosybrown', '#bc8f8f'],
    ['royalblue', '#4169e1'],
    ['saddlebrown', '#8b4513'],
    ['salmon', '#fa8072'],
    ['sandybrown', '#f4a460'],
    ['seagreen', '#2e8b57'],
    ['seashell', '#fff5ee'],
    ['sienna', '#a0522d'],
    ['silver', '#c0c0c0'],
    ['skyblue', '#87ceeb'],
    ['slateblue', '#6a5acd'],
    ['slategray', '#708090'],
    ['slategrey', '#708090'],
    ['snow', '#fffafa'],
    ['springgreen', '#00ff7f'],
    ['steelblue', '#4682b4'],
    ['tan', '#d2b48c'],
    ['teal', '#008080'],
    ['thistle', '#d8bfd8'],
    ['tomato', '#ff6347'],
    ['turquoise', '#40e0d0'],
    ['violet', '#ee82ee'],
    ['wheat', '#f5deb3'],
    ['white', '#ffffff'],
    ['whitesmoke', '#f5f5f5'],
    ['yellow', '#ffff00'],
    ['yellowgreen', '#9acd32'],
]);

it('can instantiate from hex', function (string $hex, string $res) {
    $enum = Color::fromHex($hex);
    expect($enum)->toBeInstanceOf(Color::class);
    expect($enum->hex())->toBe($res);
    expect((string) $enum)->toBe($res);
})->with([
    ['#f0f8ff', '#f0f8ff'],
    ['f0f8ff', '#f0f8ff'],
    ['#ff8', '#ffff88'],
    ['ff8', '#ffff88'],
]);

it('can instantiate from rgb', function (int $r, int $g, int $b, string $res) {
    $enum = Color::fromRgb($r, $g, $b);
    expect($enum)->toBeInstanceOf(Color::class);
    expect($enum->hex())->toBe($res);
})->with([
    [240, 248, 255, '#f0f8ff'],
    [255, 255, 136, '#ffff88'],
]);

it('can instantiate from hsv', function (int $h, int $s, int $v, string $res) {
    $enum = Color::fromHsv($h, $s, $v);
    expect($enum)->toBeInstanceOf(Color::class);
    expect($enum->hex())->toBe($res);
})->with([
    [208, 6, 100, '#f0f8ff'],
    [60, 47, 100, '#ffff87'],
]);

it('can instantiate from hsl', function (int $h, int $s, int $l, string $res) {
    $enum = Color::fromHsl($h, $s, $l);
    expect($enum)->toBeInstanceOf(Color::class);
    expect($enum->hex())->toBe($res);
})->with([
    [208, 100, 97, '#f0f8ff'],
    [60, 100, 76, '#ffff85'],
]);

it('can instantiate from cmyk', function (int $c, int $m, int $y, int $k, string $res) {
    $enum = Color::fromCmyk($c, $m, $y, $k);
    expect($enum)->toBeInstanceOf(Color::class);
    expect($enum->hex())->toBe($res);
})->with([
    [6, 3, 0, 0, '#f0f7ff'],
    [0, 1, 48, 0, '#fffc85'],
]);

it('throw invalidColorName with fromName wrong param', function () {
    expect(fn () => Color::fromName('bob'))
        ->toThrow(ColorConstructException::class, 'Color name `bob` was not recognized');
});

it('throw invalidHexFormat with fromHex wrong param', function () {
    expect(fn () => Color::fromHex('%&GHSN'))
        ->toThrow(ColorConstructException::class, 'HEX color `%&ghsn` doesn\'t match the correct format');
});

it('throw invalidHexLenght with fromHex wrong param', function () {
    expect(fn () => Color::fromHex('ffff'))
        ->toThrow(ColorConstructException::class, 'HEX color `ffff` needs to be 6 or 3 digits long');
});

it('throw invalidIntegerValue with fromRgb wrong param', function (string $param, int $r, int $g, int $b) {
    expect(fn () => Color::fromRgb($r, $g, $b))
        ->toThrow(ColorConstructException::class, "Input param `{$param}` must be between `0` and `255`");
})->with([
    ['r', 300, 200, 10],
    ['g', 34, -4, 300],
    ['b', 100, 4, 280],
]);

it('throw invalidIntegerValue with fromHsv wrong param', function (string $param, int $h, int $s, int $v) {
    expect(fn () => Color::fromHsv($h, $s, $v))
        ->toThrow(ColorConstructException::class, "Input param `{$param}` must be between `0` and `100`");
})->with([
    ['s', 100, 200, 70],
    ['v', 100, 100, 300],
    ['v', 100, 100, -300],
]);

it('not throw invalidIntegerValue with fromHsv h wrong param', function (int $h, int $s, int $v) {
    expect(fn ($h, $s, $v) => Color::fromHsv($h, $s, $v))
        ->not->toThrow(ColorConstructException::class);
})->with([
    [-20, 100, 0],
    [380, 0, 100],
]);

it('throw invalidIntegerValue with fromHsl wrong param', function (string $param, int $h, int $s, int $l) {
    expect(fn () => Color::fromHsl($h, $s, $l))
        ->toThrow(ColorConstructException::class, "Input param `{$param}` must be between `0` and `100`");
})->with([
    ['s', 100, 200, 70],
    ['l', 100, 100, 300],
    ['l', 100, 100, -300],
]);

it('not throw invalidIntegerValue with fromHsl h wrong param', function (int $h, int $s, int $l) {
    expect(fn ($h, $s, $l) => Color::fromHsl($h, $s, $l))
        ->not->toThrow(ColorConstructException::class);
})->with([
    [-20, 100, 0],
    [380, 0, 100],
]);

it('throw invalidIntegerValue with fromCmyk wrong param', function (string $param, int $c, int $m, int $y, int $k) {
    expect(fn () => Color::fromCmyk($c, $m, $y, $k))
        ->toThrow(ColorConstructException::class, "Input param `{$param}` must be between `0` and `100`");
})->with([
    ['c', -300, 200, 70, 60],
    ['m', 100, 200, 800, 50],
    ['y', 100, 100, -300, 50],
    ['k', 100, 100, 80, 150],
]);
