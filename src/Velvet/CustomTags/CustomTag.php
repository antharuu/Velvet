<?php

namespace Antharuu\Velvet\CustomTags;

use Antharuu\Velvet\Elements\HtmlElement;

abstract class CustomTag implements CustomTagInterface
{
    public string $tag;

    public function __construct()
    {
        $this->tag = $this->register();
    }

    protected function clear(array $args, HtmlElement $BlockElement): HtmlElement
    {
        array_unshift($BlockElement->block, "| " . implode(" ", $args));
        $BlockElement->content = "";

        return $BlockElement;
    }
}