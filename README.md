![Color Helper-Dark](branding/dark.png#gh-dark-mode-only)![Color Helper-Light](branding/light.png#gh-light-mode-only)
# Color Helper

[![Latest Version on Packagist](https://img.shields.io/packagist/v/datomatic/color-helper.svg?style=flat-square)](https://packagist.org/packages/datomatic/color-helper)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/datomatic/color-helper/run-tests?label=tests)](https://github.com/datomatic/color-helper/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/datomatic/color-helper/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/datomatic/color-helper/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/datomatic/color-helper.svg?style=flat-square)](https://packagist.org/packages/datomatic/color-helper)

A PHP toolkit class to manage colors functionalities

## Installation

You can install the package via composer:

```bash
composer require datomatic/color-helper
```

## Usage

```php
use Datomatic\Color\Color;
$color = Color::fromHex('#ffffff');
$color->rgb(); // ['r' => 255, 'g' => 255, 'b' => 255] 
$color->hsl(); // ['h' => 0, 's' => 0, 'l' => 100] 
$color->hsv(); // ['h' => 0, 's' => 0, 'v' => 100] 
$color->cmyk(); // ['c' => 0, 'm' => 0, 'y' => 0, 'k' => 0] 
```

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
