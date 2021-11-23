<?php

namespace Antharuu\Velvet\CustomTags;

use Antharuu\Velvet\Elements\HtmlElement;

class ScriptTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "script";
    }

    public function call(array $args, HtmlElement $BlockElement): HtmlElement
    {
        if ($BlockElement->subtag === "js") $BlockElement->subtag = "text/javascript";

        if (trim($args[0]) === "defer") {
            array_shift($args);
            $BlockElement->attributes["defer"] = [];
        }

        $href = !empty($args[0]) ? $args[0] : "#";
        $BlockElement->setAttribute("type", $BlockElement->subtag);
        $BlockElement->setAttribute("src", $href);

        return $this->clear($args, $BlockElement);
    }

}