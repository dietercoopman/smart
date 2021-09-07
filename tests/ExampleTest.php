<?php

namespace Dietercoopman\Smart\Tests;

use Dietercoopman\Smart\Smart;

class ExampleTest extends TestCase
{
    public function test_img_gets_stream()
    {
        $compiler = app(Smart::class);
        $image = $compiler->parse('<img src="./tests/test.png" smart width="400">');
        $this->assertStringContainsString("base64", $image);
        $this->assertStringContainsString("<img", $image);
    }
}
