<?php

namespace Dietercoopman\Smart;

use Dietercoopman\Smart\Factories\HtmlFactory;

class Smart
{
    public function test()
    {
        $images = collect($this->loadTags());
        $images->each(function ($image) {
            echo $this->parse($image);
        });
    }

    public function loadTags()
    {
        $tags = [];
        $tags[] = "<img src='https://new.scribo-erp.be/wp-content/uploads/2016/02/crm.png' smart>";
        $tags[] = "<img src='".storage_path('file.png')."' smart>";
        $tags[] = "<img src='https://new.scribo-erp.be/wp-content/uploads/2016/02/crm.png'>";
        $tags[] = "<a href='../storage/file.png'>";

        return $tags;
    }

    public function parse($html)
    {
        $factory = app(HtmlFactory::class);
        if ($this->isSmartHtml($html)) {
            $tag = $factory->create($html);

            return $tag->parse($html);
        }

        return $html;
    }

    private function isSmartHtml($html)
    {
        //dit moet beter , niet enkel detecteren van de string smart
        return (bool)strstr($html, "smart");
    }
}
