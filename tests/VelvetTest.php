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
            "used_extensions" => ["pug"],
            "indent_size" => 4,
            "minimize" => false
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

    public function testComment()
    {
        $V = new Velvet();
        $html = $V->parse(
            '// Oui bonjour'
        );
        $this->assertEquals(
            ''
            , $html);
    }

    public function testCommentInline()
    {
        $V = new Velvet();
        $html = $V->parse(
            'h1 Hello world // Oui bonjour'
        );
        $this->assertEquals(
            '<h1>Hello world</h1>'
            , $html);
    }

    public function testArrayAttributes()
    {
        $V = new Velvet();
        Variable::add("myH1", ["text-primary", "bg-dark", ["osef", ["mdr", "quoi", ["encore ?"]]]]);

        $html = $V->parse(
            'h1(class=$myH1) Hello world'
        );
        $this->assertEquals(
            '<h1 class="text-primary bg-dark osef mdr quoi encore ?">Hello world</h1>'
            , $html);
    }

    public function testSimpleNesting()
    {
        $V = new Velvet();
        $html = $V->parse(
            'h1 Hello 
    small world'
        );
        $this->assertEquals(
            '<h1>Hello <small>world</small></h1>'
            , $html);
    }

    public function testMultipleNesting()
    {
        $V = new Velvet();
        $html = $V->parse(
            'h1 Hello 
    small world
    | !
    span#sub-title How 
        span.are are 
            span you
        | ?'
        );
        $this->assertEquals(
            '<h1>Hello <small>world</small>!<span id="sub-title">How <span class="are">are <span>you</span></span>?</span></h1>'
            , $html);
    }

    public function testFormatNesting()
    {
        $V = new Velvet();
        $html = $V->parse(
            '#box
    #sub-box
        #sub-sub-box-1
            h1#title Hello World !
        #sub-sub-box-2
            #sub-sub-sub-box
                h2#sub-title Como esta ?
        #sub-sub-box-3
            h3#sub-sub-title Oui.'
        );
        $this->assertEquals(
            '<div id="box">
    <div id="sub-box">
        <div id="sub-sub-box-1">
            <h1 id="title">Hello World !</h1>
        </div>
        <div id="sub-sub-box-2">
            <div id="sub-sub-sub-box">
                <h2 id="sub-title">Como esta ?</h2>
            </div>
        </div>
        <div id="sub-sub-box-3">
            <h3 id="sub-sub-title">Oui.</h3>
        </div>
    </div>
</div>'
            , $html);
    }

    public function testFilterUpper()
    {
        $V = new Velvet();
        $html = $V->parse(
            'h1#title!upper Hello world !
    small ok ?'
        );
        $this->assertEquals(
            '<h1 id="title">HELLO WORLD !<small>OK ?</small></h1>'
            , $html);
    }
}