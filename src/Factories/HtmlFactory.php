<?php

namespace Dietercoopman\Smart\Factories;

class HtmlFactory
{
    public function create(string $html)
    {
        $type = $this->detectTag($html);

        switch ($type) {
            case "img":
                $class = app(ImageTag::class);

                break;
            case "a":
                $class = app(ATag::class);

                break;
            case "div":
                $class = app(DivTag::class);

                break;
            default:
                $class = app(HtmlTag::class);

                break;
        }

        return $class;
    }

    private function detectTag(string $html): string
    {
        $html = strtoupper($html);

        return strtolower(substr(explode(" ", $html)[0], 1));
    }
}
