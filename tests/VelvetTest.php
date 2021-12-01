<?php

use Antharuu\Velvet;
use Antharuu\Velvet\Variable;
use PHPUnit\Framework\TestCase;

class VelvetTest extends TestCase
{
    public function testGetSettings()
    {
        $V = new Velvet(settings: [
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
        echo "h1 Hello world";
        $html = $V->parse(ob_get_clean());

        $this->assertEquals(
            "<h1>Hello world</h1>"
            , $html);
    }

    public function testSimpleH1WithAttributes()
    {
        $V = new Velvet();
        Variable::add("primary", ["class" => ["text-primary", "bg-dark"]]);
        $html = $V->parse(
            'h1.test$primary#title(class=ok a=1 z=999 disabled) Hello world'
        );

        $this->assertEquals(
            '<h1 a="1" class="test text-primary bg-dark ok" disabled id="title" z="999">Hello world</h1>'
            , $html);
    }

    public function testWithCustomShortAttribute()
    {
        $V = new Velvet();
        $V->newShortAttribute("&", "title");
        $html = $V->parse(
            'h1#title&MySuperTitle Hello world'
        );

        $this->assertEquals(
            '<h1 id="title" title="MySuperTitle">Hello world</h1>'
            , $html);
    }
}