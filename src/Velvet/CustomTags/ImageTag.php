<?php

namespace Antharuu\Velvet\CustomTags;

use Antharuu\Velvet\Elements\HtmlElement;

class ImageTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "img";
    }

    public function call(array $args, HtmlElement $BlockElement): HtmlElement
    {
        $BlockElement->attributes["src"][] = array_shift($args);
        $BlockElement->attributes["alt"][] = implode(" ", $args);

        return $this->clear($args, $BlockElement);
    }
}