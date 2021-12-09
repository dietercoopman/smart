<?php

namespace Dietercoopman\Smart\Concerns;

use Illuminate\Support\Facades\File;
use Intervention\Image\Exception\NotSupportedException;

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

            if (isset($attributes['data-template'])) {
                self::applyTemplate($img, $attributes);
            }

            if (self::needsResizing($attributes)) {
                self::resizeImage($img, $attributes);
            }
        };
    }

    private static function resizeImage($img, $attributes)
    {
        return $img->resize(self::sanitize(optional($attributes)['width']) ?? null, self::sanitize(optional($attributes)['height']) ?? null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    }

    private static function sanitize($value)
    {
        return preg_replace('/[^0-9]/', '', $value);
    }

    public static function needsResizing($attributes): bool
    {
        return isset($attributes['width']) || isset($attributes['height']);
    }

    public static function isWebServed($src): bool
    {
        return strstr($src, 'http://') || strstr($src, 'https://');
    }

    private static function getImageStream($attributes): string
    {
        if (self::isWebServed($attributes['src'])) {
            return file_get_contents($attributes['src']);
        } else {
            return File::get($attributes['src']);
        }
    }

    private static function applyTemplate($img, $attributes)
    {
        try {
            $template = collect(config('smart.image.templates.' . $attributes['data-template']));
            return $template->each(function ($args, $method) use ($img) {
                is_array($args) ? $img->$method(...$args) : $img->$method();
            });
        } catch (NotSupportedException $e) {

        }
    }
}
