# A PHP toolkit class to manage colors functionalities

[![Latest Version on Packagist](https://img.shields.io/packagist/v/datomatic/color-helper.svg?style=flat-square)](https://packagist.org/packages/datomatic/color-helper)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/datomatic/color-helper/run-tests?label=tests)](https://github.com/datomatic/color-helper/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/datomatic/color-helper/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/datomatic/color-helper/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/datomatic/color-helper.svg?style=flat-square)](https://packagist.org/packages/datomatic/color-helper)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require datomatic/color-helper
```

## Usage

```php
use Datomatic\Color\Color;
$color = Color::fromHex('#ffffff');
echo $color->rgb();
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
