<?php namespace Dietercoopman\Smart\Concerns;

use Illuminate\Support\Facades\File;

class ImageParser
{

    /**
     * Get the closure passed to the cache method
     * @param $attributes
     * @return \Closure
     */
    public static function getCacheableImageFunction($attributes): \Closure
    {
        return function ($image) use (&$attributes) {
            $imageStream = self::getImageStream($attributes);
            $img         = $image->make($imageStream);
            if (self::needsResizing($attributes)) {
                $img->resize($attributes['width'] ?? null, $attributes['height'] ?? null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
        };
    }

    public static function needsResizing($attributes): bool
    {
        return isset($attributes['width']) || isset($attributes['height']);
    }

    public static function isWebServed(mixed $src): bool
    {
        return strstr($src, 'http://') || strstr($src, 'https://');
    }

    private static function getImageStream(mixed $attributes): string
    {
        if (self::isWebServed($attributes['src'])) {
            return file_get_contents($attributes['src']);
        } else {
            return File::get($attributes['src']);
        }
    }
}
