<?php

namespace Dietercoopman\Smart\Factories;

use Dietercoopman\Smart\Concerns\AttributeParser;
use Dietercoopman\Smart\Concerns\DownloadParser;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DivTag extends ImageTag
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

        return "<div style='background-image:url(\"" . $src . "\")'" . $this->attributesParser->rebuild($attributes) . ">";
    }

}
