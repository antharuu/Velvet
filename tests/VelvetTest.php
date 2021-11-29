<?php

use Antharuu\Velvet;
use PHPUnit\Framework\TestCase;

class VelvetTest extends TestCase
{
    public function testGetSettings()
    {
        $V = new Velvet([
            "default_path" => "velvet_views",
            "used_extensions" => ["pug"]
        ]);
        $this->assertEquals([
            "default_path" => "velvet_views",
            "layout_path" => "layout",
            "used_extensions" => ["pug"]
        ], $V::getSettings());
    }

    public function testSimpleH1()
    {
        $V = new Velvet();
        ob_start();
        echo "
h1 Hello world
";
        $html = $V->parse(ob_get_clean());

        $this->assertEquals(
            "<h1>Hello world</h1>"
            , $html);
    }
}