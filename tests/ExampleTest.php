<?php

namespace Dietercoopman\Smart\Tests;

use Dietercoopman\Smart\Smart;

class ExampleTest extends TestCase
{
    /** @test */
    public function test_images_are_loaded()
    {
        $smart = app(Smart::class);
        $this->assertIsArray($smart->loadTags());
        $smart->test();
    }

//    public function test_is_smart()
//    {
//        $smart = app(Smart::class);
//        $issmart = $smart->parse($smart->loadTags()[0]);
//        $this->assertStringContainsString("is smart",$issmart);
//    }
//
//    public function test_is_not_smart()
//    {
//        $smart = app(Smart::class);
//        $issmart = $smart->parse($smart->loadTags()[2]);
//        $this->assertStringContainsString("is not smart",$issmart);
//    }
}
