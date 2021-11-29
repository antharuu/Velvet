<?php

use Antharuu\Velvet\RegexDecoder;
use PHPUnit\Framework\TestCase;

class RegexDecoderTest extends TestCase
{
    public function testTags()
    {
        $this->assertEquals([
            "tag" => "h1",
            "rest" => "Hello world"
        ], RegexDecoder::decode("h1 Hello world"));
    }

    public function testId()
    {
        $this->assertEquals([
            "tag" => "h1",
            "rest" => "Hello world",
            "id" => ["title"]
        ], RegexDecoder::decode("h1#title Hello world"));
    }

    public function testClass()
    {
        $this->assertEquals([
            "tag" => "h1",
            "rest" => "Hello world",
            "class" => ["text-header"]
        ], RegexDecoder::decode("h1.text-header Hello world"));
    }

    public function testFilter()
    {
        $this->assertEquals([
            "tag" => "h1",
            "rest" => "Hello world",
            "filter" => ["markdown"]
        ], RegexDecoder::decode("h1!markdown Hello world"));
    }

    public function testBasicAttributes()
    {
        $this->assertEquals([
            "tag" => "h1",
            "rest" => "Hello world",
            "id" => ["title"],
            "class" => ["text-header", "text-center"],
            "filter" => ["markdown"]
        ], RegexDecoder::decode("h1.text-header#title!markdown.text-center Hello world"));
    }
}