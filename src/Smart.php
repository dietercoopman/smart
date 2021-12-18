<?php

namespace Dietercoopman\Smart;

use Dietercoopman\Smart\Factories\HtmlFactory;

class Smart
{
    use SmartHtml;

    public function parse($html)
    {
        $factory = app(HtmlFactory::class);

        if ($this->isSmartHtml($html)) {
            $tag = $factory->create($html);
            return $tag->parse($html);
        }

        return $html;
    }
}
