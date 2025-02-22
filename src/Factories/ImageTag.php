<?php

namespace Dietercoopman\Smart\Factories;

use Dietercoopman\Smart\Cache\ImageCacheController;
use Dietercoopman\Smart\Concerns\AttributeParser;
use Dietercoopman\Smart\Concerns\ImageParser;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageTag extends ImageCacheController
{
    private AttributeParser $attributesParser;

    public function __construct()
    {
        $this->attributesParser = app(AttributeParser::class);
    }

    /**
     * Parse the smart image tag
     */
    public function parse($imagTag): string
    {
        $attributes = $this->attributesParser->getAttributes($imagTag);
        $src = $this->parseAttributesAndRetreiveNewSrc($attributes);

        return "<img src='" . $src . "' " . $this->attributesParser->rebuild($attributes) . ">";
    }

    /**
     * Serve the image that has been cached
     * @param $filename
     * @return IlluminateResponse|Illuminate\Http\Response
     * @throws \Exception
     */
    public function serve($filename)
    {
        return $this->buildResponse(decrypt(cache()->get($filename)));
    }

    protected function parseAttributesAndRetreiveNewSrc(array $attributes): string
    {
        $webserved = ImageParser::isWebServed($attributes['src']);
        $needsresizing = ImageParser::needsResizing($attributes);
        $hasTemplate = isset($attributes['data-template']);

        return (! $webserved || $needsresizing || $hasTemplate) ? $this->processAndRetreiveSrc($attributes) : $attributes['src'];
    }

    /**
     * @param $attributes
     * @return string
     */
    private function processAndRetreiveSrc($attributes): string
    {
        $manager = new ImageManager(new Driver());
        $content = ImageParser::getContent($manager, $attributes);
        $cacheKey = sha1($attributes['src']);

        $src = (optional($attributes)['data-src']) ? $this->getNewCacheKey($cacheKey, $attributes['data-src']) : $cacheKey;
        cache()->put($src, encrypt($content->toJpeg()->__toString()), 100);

        return '/' . config('smart.image.path') . '/' . $src;
    }

    /**
     * Replace the originally generated cache key with a new cache key
     * @param $originalKey
     * @param $newKey
     * @return string
     */
    private function getNewCacheKey($originalKey, $newKey): string
    {
        /** todo: needs better solution for this */
        if (! Cache::has($newKey)) {
            Cache::put($newKey, Cache::get($originalKey));
            Cache::forget($originalKey);
        }

        return $newKey;
    }
}
