![Tests](https://github.com/dietercoopman/smart/workflows/run-tests/badge.svg)
![Downloads](https://img.shields.io/packagist/dt/dietercoopman/smart.svg?style=flat-square)

# This package makes your image tags smarter

With this package you enable your `<img>` tags to serve private hosted files. For examples files that are stored on a mount path and are not public accesible through a webserver.

## This is work in progress , the blade compiler is not ready yet

current implementation

```php
$compiler = app(\Dietercoopman\Smart\Smart::class);
echo $compiler->parse('<img src="../storage/file.png" width="400px" smart>');
```

future implementation

```html
<img src="../storage/file.png" width="400px" smart>
```

The packages gives you the ability to stream your private files and resize them on the fly by specifying the width and height in your tag.

## Installation

You can install the package via composer:

```bash
composer require dietercoopman/smart
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Dieter Coopman](https://github.com/dietercoopman)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
