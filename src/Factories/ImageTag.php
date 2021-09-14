<?php

namespace Dietercoopman\Smart\Factories;

use Dietercoopman\Smart\Concerns\AttributeParser;
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
        return "<img src='{$this->parseImage($imagTag)}'>";
    }

    private function parseImage(string $imagTag): string
    {
        $attributes = $this->attributesParser->getAttributes($imagTag);

        if (! $this->isWebServed($attributes['src']) || $this->needsResizing($attributes)) {
            return $this->makeWebServed($attributes);
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

        $width = $this->sanitize($width);
        $height = $this->sanitize($height);

        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        return $img->stream()->__toString();
    }

    private function sanitize(mixed $value): array | string | null
    {
        return preg_replace('/[^0-9]/', '', $value);
    }

    private function makeWebServed(mixed $attributes): string
    {
        $imageStream = File::get($attributes['src']);

        if ($this->needsResizing($attributes)) {
            $imageStream = $this->resize($imageStream, $attributes['width'] ?? null, $attributes['height'] ?? null);
        }

        return "data:image/png;base64," . base64_encode($imageStream);
    }
}
