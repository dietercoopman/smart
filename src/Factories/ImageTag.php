<?php namespace Dietercoopman\Smart\Factories;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\File;

class ImageTag
{
    public function parse($html)
    {
        $src = $this->getSrc($html);
        return "<img src='{$this->parseSrc($src)}'>";
    }

    private function getSrc($html)
    {
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        return $xpath->evaluate("string(//img/@src)");


    }

    private function parseSrc(mixed $src)
    {
        if (strstr($src, 'http://') || strstr($src, 'https://')) {
            return $src;
        }

        return "data:image/png;base64," . base64_encode(File::get($src));

    }
}
