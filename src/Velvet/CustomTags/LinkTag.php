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

        $BlockElement->attributes['href'][] = (isset($args[0]) && strlen(trim($args[0]))) ? array_shift($args) : "#";

        $target = (isset($args[0]) && str_starts_with($args[0], "_")) ? array_shift($args) : null;
        if (!is_null($target) && !empty(trim($target))) $BlockElement->attributes['target'][] = $target;

        return $this->clear($args, $BlockElement);
    }
}