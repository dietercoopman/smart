![Tests](https://github.com/dietercoopman/smart/workflows/run-tests/badge.svg)
![Downloads](https://img.shields.io/packagist/dt/dietercoopman/smart.svg?style=flat-square)

![smart image manipulation](https://banners.beyondco.de/smart.png?theme=light&packageManager=composer+require&packageName=dietercoopman%2Fsmart&pattern=architect&style=style_1&description=a+blade+component+for+easy+image+manipulation&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

# This package provides a blade component for easy image manipulation without extra coding

This packages is very handy if you want to serve private hosted images ( images on a non public path) without the need to code your own logic.  It is also very handy if you want to resize your images before sending them to the browser also without extra coding.

So this package makes it possible to
- **serve images** that are not public accessible **without coding **
- **resize images** without coding
- resizing public hosted images **without coding** 

## Blade component

Smart provides you with a **blade component** as replacement for the normal `<img>` html tag.  You can pass in all html attributes , they will be applied.   This example will **serve a file that is not public accessible** and **resize it** to 400px maintaining the aspect ratio.

```html
<x-smart-image src="../storage/file.png" width="400px" />
```

It's also possible to **handle public hosted files** but **changing** the image **size** , so execute a real resize on the image stream and not only telling the browser to show it at other dimensions.
```html
<x-smart-image src="https://raw.githubusercontent.com/dietercoopman/smart/main/tests/test.png" width="600px" />
```

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
