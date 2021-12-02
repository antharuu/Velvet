<?php

use Antharuu\Velvet\RegexDecoder;
use Antharuu\Velvet\Variable;
use PHPUnit\Framework\TestCase;

class RegexDecoderTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testTags()
    {
        $this->assertEquals([
            "tag" => "h1",
            "rest" => "Hello world"
        ], RegexDecoder::decode("h1 Hello world"));
    }

    /**
     * @throws Exception
     */
    public function testTagsSpaced()
    {
        $this->assertEquals([
            "tag" => "h1",
            "rest" => " Hello world"
        ], RegexDecoder::decode("h1  Hello world"));
    }

    /**
     * @throws Exception
     */
    public function testId()
    {
        $this->assertEquals([
            "tag" => "h1",
            "rest" => "Hello world",
            "attributes" => [
                "id" => ["title"]
            ]
        ], RegexDecoder::decode("h1#title Hello world"));
    }

    /**
     * @throws Exception
     */
    public function testClass()
    {
        $this->assertEquals([
            "tag" => "h1",
            "rest" => "Hello world",
            "attributes" => [
                "class" => ["text-header"]
            ]
        ], RegexDecoder::decode("h1.text-header Hello world"));
    }

    /**
     * @throws Exception
     */
    public function testFilter()
    {
        $this->assertEquals([
            "tag" => "h1",
            "rest" => "Hello world",
            "attributes" => [
                "filter" => ["markdown"]
            ]
        ], RegexDecoder::decode("h1!markdown Hello world"));
    }

    /**
     * @throws Exception
     */
    public function testBasicAttributes()
    {
        $this->assertEquals([
            "tag" => "h1",
            "rest" => "Hello world",
            "attributes" => [
                "class" => ["text-header", "text-center"],
                "filter" => ["markdown"],
                "id" => ["title"],
            ]
        ], RegexDecoder::decode("h1.text-header#title!markdown.text-center Hello world"));
    }

    /**
     * @throws Exception
     */
    public function testAllAttributes()
    {
        $this->assertEquals([
            "tag" => "h1",
            "rest" => "Hello world",
            "attributes" => [
                "class" => ["non"],
                "disabled" => [''],
                "filter" => ["markdown"],
                "id" => ["oui", "bonjour"],
                "test" => ["pas", "ok"],
            ]
        ], RegexDecoder::decode(
            "h1#oui.non#bonjour(test='pas')!markdown(test=ok disabled) Hello world"
        ));
    }


    /**
     * @throws Exception
     */
    public function testAllAttributesSpaced()
    {
        $this->assertEquals([
            "tag" => "h1",
            "rest" => " Hello world",
            "attributes" => [
                "class" => ["non"],
                "disabled" => [''],
                "filter" => ["markdown"],
                "id" => ["oui", "bonjour"],
                "test" => ["pas", "ok"],
            ]
        ], RegexDecoder::decode(
            "h1#oui.non#bonjour(test='pas')!markdown(test=ok disabled)  Hello world"
        ));
    }

    /**
     * @throws Exception
     */
    public function testSimpleAttributeVariable()
    {
        Variable::add("myH1", ["class" => ["text-primary", "bg-dark"], "id" => "title"]);

        $this->assertEquals([
            "tag" => "h1",
            "rest" => " Hello world",
            "attributes" => [
                "class" => ["text-primary", "bg-dark"],
                "id" => ["title"],
            ]
        ], RegexDecoder::decode(
            'h1$myH1  Hello world'
        ));
    }

    /**
     * @throws Exception
     */
    public function testSimpleValueVariable()
    {
        Variable::add("myH1", ["text-primary", "bg-dark"]);

        $this->assertEquals([
            "tag" => "h1",
            "rest" => " Hello world",
            "attributes" => [
                "class" => ["text-primary", "bg-dark"]
            ]
        ], RegexDecoder::decode(
            'h1(class=$myH1)  Hello world'
        ));
    }
}