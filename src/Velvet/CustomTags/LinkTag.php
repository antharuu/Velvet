<?php

namespace Antharuu\Velvet\CustomTags;

use Antharuu\Velvet\Elements\HtmlElement;

class LinkTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "a";
    }

    public function call(array $args, HtmlElement $BlockElement): HtmlElement
    {
        $BlockElement->attributes['href'][] = array_shift($args);

        $target = (str_starts_with($args[0], "")) ? array_shift($args) : null;
        if (!is_null($target)) $BlockElement->attributes['target'][] = $target;

        return $this->clear($args, $BlockElement);
    }
}