<?php

declare(strict_types=1);

use Datomatic\Color\Color;
use Datomatic\Color\ColorConversion;
use Datomatic\Color\Enums\ContrastLevel;
use Datomatic\Color\Exceptions\ColorConstructException;

it('converts rgb to hsv with positional arguments', function () {
    expect(ColorConversion::rgbToHsv(0, 255, 0))->toBe(['h' => 120, 's' => 100, 'v' => 100])
        ->and(ColorConversion::rgbToHsv(0, 0, 255))->toBe(['h' => 240, 's' => 100, 'v' => 100]);
});

it('normalizes negative and overflowing hues', function (int $hue, string $hex, int $normalizedHue) {
    expect(Color::fromHsv($hue, 100, 100)->hex())->toBe($hex)
        ->and(Color::fromHsv($hue, 100, 100)->hsv()['h'])->toBe($normalizedHue)
        ->and(Color::fromHsl($hue, 100, 50)->hex())->toBe($hex)
        ->and(Color::fromHsl($hue, 100, 50)->hsl()['h'])->toBe($normalizedHue);
})->with([
    'negative' => [-30, '#ff0080', 330],
    'full turn' => [360, '#ff0000', 0],
    'over a turn' => [480, '#00ff00', 120],
]);

it('finds the names case insensitively', function () {
    expect(Color::fromName(' Red ')->hex())->toBe('#ff0000');
});

it('creates transparent from its name', function () {
    expect(Color::fromName('transparent')->alpha())->toBe(0.0)
        ->and(Color::fromName('transparent')->hex())->toBe('#00000000');
});

it('reads the alpha channel from hex', function (string $hex, string $expectedHex, float $alpha) {
    $color = Color::fromHex($hex);

    expect($color->hex())->toBe($expectedHex)
        ->and($color->alpha())->toBe($alpha);
})->with([
    '4 digits' => ['#f008', '#ff000088', 0.533],
    '8 digits' => ['ff000080', '#ff000080', 0.502],
    'opaque 8 digits' => ['#ff0000ff', '#ff0000', 1.0],
]);

it('parses css colors', function (string $css, string $hex) {
    expect(Color::parse($css)->hex())->toBe($hex);
})->with([
    'name' => ['RebeccaPurple', '#663399'],
    'transparent' => ['transparent', '#00000000'],
    'hex' => ['#0f0', '#00ff00'],
    'hex without hash' => ['00ff00', '#00ff00'],
    'rgb' => ['rgb(255, 0, 0)', '#ff0000'],
    'rgba' => ['rgba(255, 0, 0, 0.5)', '#ff000080'],
    'rgb space syntax' => ['rgb(255 0 0 / 50%)', '#ff000080'],
    'rgb percentages' => ['rgb(100%, 0%, 0%)', '#ff0000'],
    'hsl' => ['hsl(120, 100%, 50%)', '#00ff00'],
    'hsl degrees' => ['hsl(240deg 100% 50%)', '#0000ff'],
    'hsla' => ['hsla(0, 100%, 50%, 0.25)', '#ff000040'],
]);

it('rejects unparsable css colors', function (string $css) {
    Color::parse($css);
})->throws(ColorConstructException::class)->with([
    'unknown name' => ['notacolor'],
    'missing channel' => ['rgb(255, 0)'],
    'not a number' => ['rgb(255, x, 0)'],
    'out of range' => ['rgb(300, 0, 0)'],
    'invalid alpha' => ['rgba(255, 0, 0, 2)'],
]);

it('exports css strings', function (Color $color, string $rgb, string $hsl) {
    expect($color->rgbString())->toBe($rgb)
        ->and($color->hslString())->toBe($hsl);
})->with([
    'opaque' => [Color::fromRgb(255, 0, 0), 'rgb(255, 0, 0)', 'hsl(0, 100%, 50%)'],
    'translucent' => [Color::fromRgb(0, 0, 255, 0.5), 'rgba(0, 0, 255, 0.5)', 'hsla(240, 100%, 50%, 0.5)'],
]);

it('replaces the alpha channel', function () {
    $color = Color::fromHex('#ff0000');
    $translucent = $color->withAlpha(0.5);

    expect($translucent->alpha())->toBe(0.5)
        ->and($translucent->rgb())->toBe(['r' => 255, 'g' => 0, 'b' => 0])
        ->and($color->alpha())->toBe(1.0);
});

it('rejects an alpha out of range', function (float $alpha) {
    Color::fromRgb(0, 0, 0, $alpha);
})->throws(ColorConstructException::class, 'Input param `alpha` must be between `0` and `1`')->with([-0.1, 1.1]);

it('manipulates the lightness and saturation', function (string $method, int $amount, string $hex) {
    expect(Color::fromHex('#cc3333')->{$method}($amount)->hex())->toBe($hex);
})->with([
    'lighten' => ['lighten', 20, '#e08585'],
    'darken' => ['darken', 20, '#7a1f1f'],
    'saturate' => ['saturate', 20, '#e61a1a'],
    'desaturate' => ['desaturate', 20, '#b34d4d'],
    'darken to black' => ['darken', 100, '#000000'],
    'lighten to white' => ['lighten', 100, '#ffffff'],
]);

it('rejects a manipulation amount out of range', function () {
    Color::fromHex('#cc3333')->lighten(101);
})->throws(ColorConstructException::class, 'Input param `amount` must be between `0` and `100`');

it('rotates, inverts and converts to grayscale', function () {
    $red = Color::fromHex('#ff0000');

    expect($red->rotate(120)->hex())->toBe('#00ff00')
        ->and($red->rotate(-120)->hex())->toBe('#0000ff')
        ->and($red->invert()->hex())->toBe('#00ffff')
        ->and(Color::fromHex('#cc3333')->grayscale()->hex())->toBe('#808080');
});

it('keeps the alpha channel when manipulating', function () {
    $color = Color::fromRgb(204, 51, 51, 0.4);

    expect($color->lighten(10)->alpha())->toBe(0.4)
        ->and($color->rotate(90)->alpha())->toBe(0.4)
        ->and($color->invert()->alpha())->toBe(0.4);
});

it('does not change the original color', function () {
    $color = Color::fromHex('#cc3333');

    $color->lighten(20);
    $color->invert();
    $color->withAlpha(0.1);

    expect($color->hex())->toBe('#cc3333');
});

it('computes the WCAG luminance and contrast', function (string $first, string $second, float $ratio) {
    expect(round(Color::fromHex($first)->contrast(Color::fromHex($second)), 2))->toBe($ratio);
})->with([
    'black on white' => ['#000000', '#ffffff', 21.0],
    'same color' => ['#777777', '#777777', 1.0],
    'gray on white' => ['#777777', '#ffffff', 4.48],
    'blue on white' => ['#0000ff', '#ffffff', 8.59],
]);

it('keeps luminosity as an alias of luminance', function () {
    $color = Color::fromHex('#777777');

    expect($color->luminosity())->toBe($color->luminance());
});

it('checks the WCAG contrast levels', function () {
    $white = Color::fromHex('#ffffff');
    $gray = Color::fromHex('#777777');

    expect($gray->meetsContrast($white))->toBeFalse()
        ->and($gray->meetsContrast($white, largeText: true))->toBeTrue()
        ->and(Color::fromHex('#595959')->meetsContrast($white, ContrastLevel::AAA))->toBeTrue()
        ->and($gray->meetsContrast($white, ContrastLevel::AAA, largeText: true))->toBeFalse();
});

it('finds the color with the best contrast', function () {
    $white = Color::fromHex('#ffffff');

    expect($white->bestContrastColor([Color::fromHex('#000000'), Color::fromHex('#eeeeee')])->hex())->toBe('#000000')
        ->and($white->bestContrastColor([Color::fromHex('#eeeeee'), Color::fromHex('#333333'), Color::fromHex('#999999')])->hex())->toBe('#333333');
});

it('rejects an empty list for the best contrast color', function () {
    Color::fromHex('#fff')->bestContrastColor([]);
})->throws(ColorConstructException::class, 'At least one color is required');

it('rejects an empty list for the average color', function () {
    Color::averageColors([]);
})->throws(ColorConstructException::class, 'At least one color is required');

it('mixes two colors', function (float $ratio, string $hex) {
    expect(Color::fromHex('#ff0000')->mix(Color::fromHex('#0000ff'), $ratio)->hex())->toBe($hex);
})->with([
    'first' => [0.0, '#ff0000'],
    'half' => [0.5, '#800080'],
    'second' => [1.0, '#0000ff'],
]);

it('mixes the alpha channel', function () {
    expect(Color::fromRgb(0, 0, 0, 0.0)->mix(Color::fromRgb(0, 0, 0))->alpha())->toBe(0.5);
});

it('rejects a mix ratio out of range', function (float $ratio) {
    Color::mixColors(Color::fromHex('#000'), Color::fromHex('#fff'), $ratio);
})->throws(ColorConstructException::class, 'Input param `ratio` must be between `0` and `1`')->with([-0.5, 1.5]);

it('averages colors', function () {
    $average = Color::averageColors([
        Color::fromHex('#ff0000'),
        Color::fromHex('#00ff00'),
        Color::fromRgb(0, 0, 255, 0.4),
    ]);

    expect($average->hex(false))->toBe('555555cc')
        ->and($average->alpha())->toBe(0.8);
});

it('tells light and dark colors apart', function () {
    expect(Color::fromHex('#ffffff')->isLight())->toBeTrue()
        ->and(Color::fromHex('#000000')->isDark())->toBeTrue()
        ->and(Color::fromHex('#808080')->isLight(100))->toBeTrue()
        ->and(Color::fromHex('#808080')->isLight(200))->toBeFalse();
});

it('compares colors', function () {
    expect(Color::fromHex('#ff0000')->equals(Color::fromRgb(255, 0, 0)))->toBeTrue()
        ->and(Color::fromHex('#ff0000')->equals(Color::fromRgb(255, 0, 0, 0.5)))->toBeFalse()
        ->and(Color::fromHex('#ff0000')->equals(Color::fromHex('#ff0001')))->toBeFalse();
});

it('clones a color', function () {
    $color = Color::fromRgb(10, 20, 30, 0.5);
    $clone = $color->clone();

    expect($clone)->not->toBe($color)
        ->and($clone->equals($color))->toBeTrue();
});

it('creates random colors', function () {
    $rgb = Color::random()->rgb();

    expect($rgb['r'])->toBeBetween(0, 255)
        ->and($rgb['g'])->toBeBetween(0, 255)
        ->and($rgb['b'])->toBeBetween(0, 255);
});

it('casts to the hex string', function () {
    expect((string) Color::fromRgb(255, 0, 0, 0.5))->toBe('#ff000080');
});
