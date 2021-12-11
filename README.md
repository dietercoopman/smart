![Downloads](https://img.shields.io/packagist/dt/dietercoopman/smart.svg?style=flat-square)

![smart image manipulation](https://banners.beyondco.de/smart.png?theme=light&packageManager=composer+require&packageName=dietercoopman%2Fsmart&pattern=architect&style=style_1&description=a+blade+component+for+easy+image+manipulation&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

# Blade components for easy image manipulation and file downloads

This package makes it possible to

- **serve images** from anywhere, this might be a public path , a private path or a Laravel disk
- **resize images** not only by defining height and width in the html image tag but by really resizing the content that is passed to the browser
- **apply templates** to images, change the settings for all images from one place
- automatically **cache** your images
- apply the **full intervention/image API** to an image
- **download files** from anywhere, this might be a public path , a private path or a Laravel disk

## Typical use case

### For smart image

Serving images that are stored wherever you want, changing the size and look&feel of an image without changing the original source. So you can use 1 image to once serve them for example grey on an overview page, but full color on a detail page.

### For smart download

Downloading files that are stored wherever you want this can be your storage folder a Laravel disk or a https path

## Installation

You can install the package via composer

```bash
composer require dietercoopman/smart
```

you can optionaly publish the config file if you want to use templates or change some settings ( see advanced usage with templates )

```bash
php artisan vendor:publish --tag=smart-config
```

# Smart Image

## Full example

In this example the images are stored on S3. We want the images to be served all grey and at the same height, but also rotated 15 degrees. They are encoded as webp and given a good name, search engines will love them, all with 1 smart tag.

![full example](https://user-images.githubusercontent.com/4672752/145644476-61fdea22-7292-49db-af3e-8fc820ca4127.png)

## The blade component

Smart provides you with a **blade component** as replacement for the normal `<img>` html tag. You can pass in all default html attributes like the `class` tag they will be passed to the rendered html.

## The attributes

### src

Specify the source of your image with `src`, this can be a https path, or a location on your server ( like /mnt/images ) or a Laravel disk to unlock serving images from S3, Dropbox or other custom filesystem.

### data-disk

With this `data-disk` attribute you tell smart on which Laravel disk the src specified can be found.

### data-src

Specify the source as exposed to the browser with `data-src`. That is the source as shown in the rendered html, so you can expose friendly names to end users or search engines

### data-template

Specify the template to apply with `data-template` ( see advanced usage with templates ) to apply a pre-configured template to your images.

## Examples

### Base example

This example will **serve a file that is stored in the storage folder**

```html

<x-smart-image src="{{ storage_path('smart.png') }}"/>
```

### Loading images from Laravel disks

This example loads an image from a S3 compatible Laravel disk with `data-disk`

```html

<x-smart-image data-disk="s3" src="logos/mybrand.jpg"/>
```

### Resizing images

This example will **serve a file that is stored in the storage folder** and **resize it** to 400px ( real file resize ! ) maintaining the aspect ratio.

```html

<x-smart-image src="{{ storage_path('smart.png') }}" width="400px"/>
```

### Changing the name of the served content

The default name of the served images is a cache key, if you want to give it a more friendly name you can specify it with `data-src`

```html

<x-smart-image src="{{ storage_path('smart.png') }}" data-src="branding.png"/>
```

### Using templates

With templates you can apply a predefined set of settings to your images. Typically handy if you are using images in several places of for example an e-commerce site.

```html

<x-smart-image src="products/product1.jpg" data-template="small" data-disk="s3" data-src="friendly-product-name.jpg"/>
```

## Caching

The images are cached with the intervention/image cache. Default, the package will generate a key to store the images in the cache. This key will be used to build the src of the file, making it possible for browsers to cache the image. This key is random generated, but you can override it if you want a more descriptive name for your images ( see `data-src` ) .

![cache example](cache.png)

## Advanced usage with templates

Via the `data-template`attribute you can specify which template your image should use. The templates are configurable in the `config/smart.php` config file.

Here's the default config

```
<?php

$constraint = function ($constraint) {
    $constraint->aspectRatio();
};

return [
    'image' => [
        'path'      => 'smart',
        'templates' => [
            'small' => [
                'resize' => [200, null, $constraint],
            ],
            'big'   => [
                'resize' => [500, null, $constraint],
            ]
        ]
    ],
    'download' => [
        'path' => 'smart/downloads',
        'default-text' => 'download this file'
    ]
];
```

The `path` key defines the url prefix for smart, it defaults to smart but it can be whatever you want.

There are two templates defined by default, `small` and `big`. Within the configuration you can define what settings need to be applied to your images.  
The possible settings are the method names as stated in the [intervention image](http://image.intervention.io/) API.  
You can create as many template as you want.

For example, if you want to use the `resize` method from intervention/image then you define a resize array with the arguments as array value, defined as a sub array. All methods from the api can be used. Here's an example of a config and the result

![template example](https://user-images.githubusercontent.com/4672752/145472356-19e8982e-6937-49f2-9c71-d173091a127a.png)

# Smart download

Smart download makes it possible to download any type of document with a simple tag. No need to program a backend portion of code to retrieve file streams and serve them, its
all handled by smart.

## The blade component

Smart download provides you with a href tag. You can pass in all default html attributes like the `class` tag they will be passed to the rendered html.  
You can use a slot as visualisation for the link, the defaults are configured in the `default-text` parameter from the config.

## The attributes for  x-smart-download

### src

Specify the source of your download file with `src`, this can be a https path, or a location on your server ( like /mnt/images ) or a Laravel disk to unlock serving images from S3, Dropbox or other custom filesystem.

### data-disk

With this `data-disk` attribute you tell smart on which Laravel disk the src specified can be found.

## Examples

### A base example

This example lets you download a manual that is stored in your storage path. 

```
<x-smart-download src="{{ storage_path('manual.pdf') }}" target="_blank" />
```

### An advanced example with an image as visualisation

This example combines the image and the download tag, the image is passed in the default slot so you have a visual link. 

```
<x-smart-download src="logo.png" data-disk="s3" target="_blank" />
    <x-smart-image src="logo.png" data-template="small" data-disk="s3" />
</x-smart-download>
```

### A rendered output example 

This is the rendered output from an example as above, combining the smart-download and smart-image tag

![Schermafbeelding 2021-12-11 om 14 05 07](https://user-images.githubusercontent.com/4672752/145677569-b92bb779-80a6-40f1-b0b2-ec83e7b4ff35.png)

### Some storytelling on use cases 

![Schermafbeelding 2021-12-11 om 17 42 17](https://user-images.githubusercontent.com/4672752/145684566-daf6c873-d604-4750-a089-e78f2a096af4.png)
![Schermafbeelding 2021-12-11 om 17 33 42](https://user-images.githubusercontent.com/4672752/145684515-a5843937-3bb0-4c4a-97e2-abe6bb62bcbd.png)
![Schermafbeelding 2021-12-11 om 17 33 52](https://user-images.githubusercontent.com/4672752/145684485-12771757-b4e4-4e90-819e-7cb6bf4e98dd.png)
![Schermafbeelding 2021-12-11 om 17 34 00](https://user-images.githubusercontent.com/4672752/145684520-b9239576-1940-4ebe-8892-7563893593b1.png)


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
