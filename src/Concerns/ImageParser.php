<?php

namespace Dietercoopman\Smart\Concerns;

use Intervention\Image\Exception\NotSupportedException;

class ImageParser extends Parser
{
    /**
     * Get the closure passed to the cache method
     * @param $attributes
     * @return \Closure
     */
    public static function getCacheableImageFunction($attributes): \Closure
    {
        return function ($image) use (&$attributes) {
            $imageStream = self::getStream($attributes);
            $img = $image->make($imageStream);

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

    private static function applyTemplate($img, $attributes)
    {
        try {
            $template = collect(config('smart.image.templates.' . $attributes['data-template']));

            return $template->each(function ($args, $method) use ($img) {
                $args = self::checkIfLastArgumentIsArrayAndConvertToMethodIfSo($args);
                is_array($args) ? $img->$method(...$args) : $img->$method();
            });
        } catch (NotSupportedException $e) {
        }
    }

    private static function checkIfLastArgumentIsArrayAndConvertToMethodIfSo($args): array
    {
        $last = end($args);

        if (is_array($last)) {
            array_pop($args);
            $callback = function ($constraint) use ($last) {
                foreach ($last as $method => $args) {
                    if (is_numeric($method)) {
                        $method = $args;
                        $args = [];
                    }
                    $constraint->$method(...$args);
                }
            };
            $args[] = $callback;
        }

        return $args;
    }
}
