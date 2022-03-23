<?php

namespace Dietercoopman\Smart\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Parser
{
    protected static function sanitize($value)
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

    public static function getStream($attributes): string
    {
        try {
            if (self::isWebServed($attributes['src'])) {
                return file_get_contents($attributes['src']);
            } elseif (isset($attributes['data-disk'])) {
                return Storage::disk($attributes['data-disk'])->get($attributes['src']);
            } else {
                return File::get($attributes['src']);
            }
        } catch (\Throwable $e) {
            return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAA1JREFUGFdj+P///38ACfsD/QVDRcoAAAAASUVORK5CYII=';
        }
    }
}
