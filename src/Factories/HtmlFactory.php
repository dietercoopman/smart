<?php namespace Dietercoopman\Smart\Factories;


class HtmlFactory
{
    public function create(string $html)
    {
        $type = $this->detectTag($html);
        return match ($type) {
            "img" => app(ImageTag::class),
            default => app(HtmlTag::class)
        };
    }

    private function detectTag(string $html): string
    {
        $html = strtoupper($html);
        return strtolower(substr(explode(" ", $html)[0], 1));
    }
}
