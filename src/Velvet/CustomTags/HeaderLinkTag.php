<?php

namespace Antharuu\Velvet\CustomTags;

use Antharuu\Velvet\Elements\HtmlElement;

class HeaderLinkTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "link";
    }

    public function call(array $args, HtmlElement $BlockElement): HtmlElement
    {
        if ($BlockElement->subtag === "css") $BlockElement->subtag = "stylesheet";

        $href = !empty($args[0]) ? $args[0] : "#";
        $BlockElement->setAttribute("rel", $BlockElement->subtag);
        $BlockElement->setAttribute("href", $href);

        return $this->clear($args, $BlockElement);
    }

}