<?php

namespace Dietercoopman\Smart;

use Dietercoopman\Smart\Concerns\AttributeParser;

trait SmartHtml
{
    private function isSmartHtml($html): bool
    {
        /** @var AttributeParser $attributeParser */
        $attributeParser = app(AttributeParser::class);
        return (bool)optional($attributeParser->getAttributes($html))['smart'];
    }
}
