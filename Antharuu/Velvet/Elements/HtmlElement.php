<?php

namespace Antharuu\Velvet\Elements;

class HtmlElement
{
    public string $tag = "div";

    public ?string $subTag = null;
    public array $attributes = [];

    public string $line;
    public array $lines = [];

    public string $content;
    public ?array $block = [];

    public int $indent = 0;

    public bool $isInline = false;
    public bool $isParentInline = false;
    public bool $isEmpty = true;

    public function __construct(array $parts)
    {
        if (isset($parts['tag'])) $this->tag = $parts['tag'] ?? "div";
        if (isset($parts['subTag'])) $this->subTag = $parts['subTag'];
        if (isset($parts['attributes'])) $this->attributes = $parts['attributes'];
        if (isset($parts['rest'])) $this->content = $parts['rest'];
    }
}