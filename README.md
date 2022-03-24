![Downloads](https://img.shields.io/packagist/dt/dietercoopman/smart.svg?style=flat-square)

![smart image manipulation](https://banners.beyondco.de/smart.png?theme=light&packageManager=composer+require&packageName=dietercoopman%2Fsmart&pattern=architect&style=style_1&description=Blade+components+for+easy+image+manipulation+and+file+downloads&md=1&showWatermark=0&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

# Blade components for easy image manipulation and file downloads

This package makes it possible to

- **serve images** from anywhere, this might be a public path , a private path or a Laravel disk
- **resize images** not only by defining height and width in the html image tag but by really resizing the content that is passed to the browser
- **apply templates** to images, change the settings for all images from one place
- automatically **cache** your images
- apply the **full intervention/image API** to an image
- **download files** from anywhere, this might be a public path , a private path or a Laravel disk
- use **smart-div** to add background images to div blocks

## Typical use case

### For smart image

Serving images that are stored wherever you want, changing the size and look&feel of an image without changing the original source. So you can use 1 image to once serve them for example grey on an overview page, but full color on a detail page.

### For smart download

Downloading files that are stored wherever you want this can be your storage folder a Laravel disk or a https path

### For smart div

Sometimes you have to add background images to div blocks, this can be achieved with smart-div.  You can apply templates to the 
background images.

## Watch me explaining what smart is on YouTube
[![Schermafbeelding 2021-12-12 om 15 26 36](https://user-images.githubusercontent.com/4672752/145716421-cd75f419-0478-4f14-9522-703c2c76c84f.png)](https://www.youtube.com/watch?v=XuHM_9lhClE)

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

![overview](https://user-images.githubusercontent.com/4672752/145693397-f960d88b-dc22-40f2-8699-e0351f6db632.png)

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

return [
    'image'    => [
        'path'      => 'smart',
        'templates' => [
            'small' => [
                'resize' => [200, null, ['aspectRatio']],
            ],
            'big'   => [
                'resize' => [500, null, ['aspectRatio']],
            ]
        ],
        'file-not-found' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAA1JREFUGFdj+P///38ACfsD/QVDRcoAAAAASUVORK5CYII='
    ],
    'download' => [
        'path'         => 'smart/downloads',
        'default-text' => 'download this file'
    ]
];
```

The `path` key defines the url prefix for smart, it defaults to smart but it can be whatever you want.

There are two templates defined by default, `small` and `big`. Within the configuration you can define what settings need to be applied to your images.  
The possible settings are the method names as stated in the [intervention image](http://image.intervention.io/) API.  
You can create as many template as you want.

For example, if you want to use the `resize` method from intervention/image then you define a resize array with the arguments as array value, defined as a sub array. All methods from the api can be used. Here's an example of a config and the result

![fullexample](https://user-images.githubusercontent.com/4672752/145709051-f5acc5b4-c480-4063-ad3d-bb0bac055274.png)

if a given source is not found than the image defined in `file-not-found` is returned (default a 1x1 png), here you can specify any image stream or image path.

# Using the full API of intervention/image

You can even go further, you can apply the full API of intervention/image by passing arrays, this examples draws a rectangle onto your resized image.  The most simple way of doing it is by definig a new array with the method names of the callback as array keys and the arguments as array value, then passing this array as if you would pass a callback to an intervention/image method.

```
<?php

$rectangle = [
    'background' => ['rgba(255, 255, 255, 0.5)'],
    'border'     => [10, '#CCC']
];

return [
    'image' => [
        'path'      => 'smart',
        'templates' => [
            'rotated' => [
                'resize'    => [null, 500, ['aspectRatio']],
                'rectangle' => [5, 5, 195, 195, $rectangle],
            ]           
        ]
    ]
];
```

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

# Smart div

With smart div it's possible to apply background images on div's.  The images can be stored everywhere, just like with smart-image.
The documentation for smart div is the same as for smart-image. The only difference is that it will render a div with a background-image
applied to it.

```html
<x-smart-div src="smart.png" data-src="background.png"></x-smart-div>
```

This renders as the following html

```html
<div style='background-image:url("/smart/background.png")'></div>
```

# Some storytelling on use cases 

```html
<h1>Base examples</h1>
<!-- I have a file in a public path -->
<img src="smart.png" /><br />
<!-- ☝ this works, cool ... -->

<h1>Resizing images without smart</h1>
<!-- WITHOUT SMART -->
<!-- I want to make it smaller, without changeing my source -->
<img src="smart.png" width="200px" /><br/>
<!-- ☝ the file size is not changed :-( , it's the same number of KB's  76.4 KBs 
, you might don't care but ... -->

<!-- Ok lets make it some more challenging  -->
<img src="big.png" width="200px" /><br />
<!-- ☝ the file size is not changed :-( , it's the same number of KB's  445 KBs , you might care :-)
assume 25 images on your screen , thats more than 10MB ... -->

<h1>Resizing images with smart</h1>
<!-- WITH SMART -->
<!-- Let's see what smart does with the same use case -->
<x-smart-image src="smart.png" width="200px" data-src="smart.png" /><br />
<!-- ☝ the file size changed :-) , the file size is shrinked => result 12 KBs ... -->

<!-- Let's see what smart does with the same use case for the big image -->
<x-smart-image src="big.png" width="200px" data-src="big-shrinked.png" /><br />
<!-- ☝ the file size changed :-) , the file size is shrinked => result 9.4 KBs ... 
assume 25 images on your screen => 235 KBs , that's about 9.8MB less ... -->

<h1>Changing the look and feel of an image</h1>
<!-- WITHOUT SMART -->
<!-- I want to rotate the image, hmm ...  -->
<img src="smart.png" width="100px" style="transform: rotate(45deg)" /><br />
<!-- ok its rotated , but it's still too big in filesize and meh that css ... -->

<!-- WITH SMART -->
<!-- Let's see what smart does with the same use case -->
<x-smart-image src="smart.png" data-template="rotated" data-src="smart_rotated.png" /><br />
<!-- ☝ the file size changed :-) , the file size is shrinked 6.2 KBs ... -->

<h1>Advanced examples with templates</h1>
<!-- lets go crazy -->
<x-smart-image src="big.png" data-template="crazy" data-src="big-crazy.png" /><br />
<!-- ☝ fun isn't it , without touching the original image -->

<h1>Files that are not serveable by your webbrowser</h1>
<!-- And now the tought part ... not for smart but for the img tag -->

<!-- WITHOUT SMART -->
<!-- I don't want my files in that private public path , I want them on S3 -->
<img src="{{ Storage::disk('s3')->get('smart.png') }}" />
<!-- ☝ this doesn't work ... -->

<!-- WITH SMART -->
<!-- I don't want my files in that public path , I want them on S3 -->
<x-smart-image data-disk="s3" src="another_big.png" data-template="crazy" /><br />
<!-- or in your storage folder -->
<x-smart-image src="{{ storage_path('smart.png') }}" data-template="crazy" />
<!-- hell yeah ! -->

<h1>Downloads with smart</h1>
<!-- WITHOUT SMART -->
<a href="{{ Storage::disk('s3')->get('smart.png') }}" />
<!-- ☝ this doesn't work ... -->

<!-- downloads WITH SMART -->
<!-- Now , our customers might have the ability to download images -->
<x-smart-download data-disk="s3" src="another_big.png" /><br />
<!-- Or, with slots -->
<x-smart-download data-disk="s3" src="another_big.png">Download this photo</x-smart-download><br/>
<!-- Or, event better -->
<x-smart-download data-disk="s3" src="another_big.png">
    <x-smart-image data-disk="s3" src="another_big.png" data-template="crazy" />
</x-smart-download>
```
![resize](https://user-images.githubusercontent.com/4672752/145706393-f9f6fa47-52c0-480c-b8ee-a87ab945a826.png)
![advanced](https://user-images.githubusercontent.com/4672752/145706395-c9f82468-d63c-4848-90de-7a61e7554a78.png)
![downloads](https://user-images.githubusercontent.com/4672752/145706396-e262b44f-232e-4cbb-acd3-a30fcb322465.png)
![downloadssmart](https://user-images.githubusercontent.com/4672752/145706426-7d68e792-fcdc-4676-95eb-ba0e88981247.png)


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
