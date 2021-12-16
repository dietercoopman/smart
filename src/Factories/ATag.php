<?php

namespace Dietercoopman\Smart\Factories;

use Dietercoopman\Smart\Concerns\AttributeParser;
use Dietercoopman\Smart\Concerns\DownloadParser;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ATag
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

        return "<a href='" . $this->getLink($attributes) . "' " . $this->attributesParser->rebuild($attributes) . ">__slot__</a>";
    }

    private function getLink(array $attributes): string
    {
        return $this->processAndRetreiveNewLink($attributes);
    }

    /**
     * @param $attributes
     * @return string
     */
    private function processAndRetreiveNewLink($attributes): string
    {
        $sha1 = $this->saveAttributesToCacheAndReturnCacheKey($attributes);

        return '/' . config('smart.download.path') . '/' . $sha1.'/'.basename($attributes['src']);
    }

    public function download()
    {
        $attributes = $this->getAttributesForRequest();

        return $this->buildResponse($attributes);
    }

    private function saveAttributesToCacheAndReturnCacheKey(array $attributes)
    {
        $jsonAttributes = json_encode($attributes);
        $sha1 = sha1($jsonAttributes);
        $hash = encrypt($jsonAttributes);
        if (! Cache::has($sha1)) {
            Cache::put($sha1, $hash);
        }

        return $sha1;
    }

    private function getAttributesForRequest()
    {
        $segments = request()->segments();
        end($segments);
        $sha1 = prev($segments);

        $encryptedAttributes = Cache::get($sha1);

        return json_decode(decrypt($encryptedAttributes), true);
    }

    private function buildResponse($attributes)
    {
        $response = response()->stream(function () use ($attributes) {
            echo DownloadParser::getStream($attributes);
        });

        $name = basename($attributes['src']);
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $name, str_replace('%', '', Str::ascii($name)));

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
