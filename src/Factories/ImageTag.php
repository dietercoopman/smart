<?php

namespace Dietercoopman\Smart\Factories;

use Dietercoopman\Smart\Concerns\AttributeParser;
use Dietercoopman\Smart\Concerns\ImageParser;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Intervention\Image\ImageCacheController;
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
        $src        = $this->parseAttributesAndRetreiveNewSrc($attributes);

        return "<img src='" . $src . "'" . $this->attributesParser->rebuild($attributes) . ">";
    }

    /**
     * Serve the image that has been cached
     * @param $filename
     * @return IlluminateResponse|Illuminate\Http\Response
     * @throws \Exception
     */
    public function serve($filename)
    {
        return $this->buildResponse(cache()->get($filename));
    }

    private function parseAttributesAndRetreiveNewSrc(array $attributes): string
    {
        $webserved     = ImageParser::isWebServed($attributes['src']);
        $needsresizing = ImageParser::needsResizing($attributes);
        $hasTemplate   = isset($attributes['data-template']);

        return (!$webserved || $needsresizing || $hasTemplate) ? $this->processAndRetreiveSrc($attributes) : $attributes['src'];
    }

    /**
     * @param $attributes
     * @return string
     */
    private function processAndRetreiveSrc($attributes): string
    {
        $manager = new ImageManager(Config::get('image'));
        $content = $manager->cache(ImageParser::getCacheableImageFunction($attributes), 3600, true);
        $src     = (optional($attributes)['data-src']) ? $this->getNewCacheKey($content->cachekey, $attributes['data-src']) : $content->cachekey;

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
        Cache::put($newKey, Cache::get($originalKey));
        Cache::forget($originalKey);

        return $newKey;
    }
}
