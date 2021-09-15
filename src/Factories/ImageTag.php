<?php

namespace Dietercoopman\Smart\Factories;

use Dietercoopman\Smart\Concerns\AttributeParser;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class ImageTag
{
    private AttributeParser $attributesParser;

    public function __construct()
    {
        $this->attributesParser = app(AttributeParser::class);
    }

    public function parse($imagTag): string
    {
        $attributes = $this->attributesParser->getAttributes($imagTag);

        return "<img src='{$this->parseImage($attributes)}' ".$this->attributesParser->rebuild($attributes).">";
    }

    private function parseImage(array $attributes): string
    {

        if (!$this->isWebServed($attributes['src']) || $this->needsResizing($attributes)) {
            return $this->processImage($attributes);
        }

        return $attributes['src'];
    }

    private function needsResizing($attributes): bool
    {
        return isset($attributes['width']) || isset($attributes['height']);
    }

    private function isWebServed(mixed $src): bool
    {
        return strstr($src, 'http://') || strstr($src, 'https://');
    }

    private function resize($imageStream, mixed $width, mixed $height): string
    {
        $img = Image::make($imageStream);

        $width  = $this->sanitize($width);
        $height = $this->sanitize($height);

        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        return $img->stream()->__toString();
    }

    private function sanitize(mixed $value): array|string|null
    {
        return preg_replace('/[^0-9]/', '', $value);
    }

    private function processImage(mixed $attributes): string
    {
        return Cache::get(sha1(json_encode($attributes)), function () use ($attributes) {
            return $this->getContentsAndCache($attributes);
        });
    }

    private function getImageStream(mixed $attributes) : string
    {
        if ($this->isWebServed($attributes['src'])) {
            return file_get_contents($attributes['src']);
        } else {
            return File::get($attributes['src']);
        }

    }

    private function getContentsAndCache($attributes)
    {
        $imageStream = $this->getImageStream($attributes);
        if ($this->needsResizing($attributes)) {
            $imageStream = $this->resize($imageStream, $attributes['width'] ?? null, $attributes['height'] ?? null);
        }
        Cache::put(sha1(json_encode($attributes)), "data:image/png;base64," . base64_encode($imageStream));
        return "data:image/png;base64," . base64_encode($imageStream);
    }
}
