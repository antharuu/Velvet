<?php

namespace Antharuu\Velvet\CustomTags;

use Antharuu\Velvet\Elements\HtmlElement;

class DoctypeTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "doctype";
    }

    public function call(array $args, HtmlElement $BlockElement): HtmlElement
    {
        $BlockElement->tag = "!DOCTYPE";
        $BlockElement->attributes["html"] = [];
        return $this->clear($args, $BlockElement);
    }
}