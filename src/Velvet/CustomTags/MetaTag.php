<?php

namespace Antharuu\Velvet\CustomTags;

use Antharuu\Velvet\Elements\HtmlElement;

class MetaTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "meta";
    }

    public function call(array $args, HtmlElement $BlockElement): HtmlElement
    {
        switch ($BlockElement->subtag) {
            case "charset":
                $BlockElement = $this->charset($args, $BlockElement);
                break;
            case "viewport":
                $BlockElement = $this->viewport($args, $BlockElement);
                break;
            default;
        }

        return $BlockElement;
    }

    private function charset(array $args, HtmlElement $BlockElement): HtmlElement
    {
        $charset = !empty($args[0]) ? $args[0] : "UTF-8";
        $BlockElement->setAttribute("charset", $charset);

        return $this->clear($args, $BlockElement);
    }

    private function viewport(array $args, HtmlElement $BlockElement): HtmlElement
    {
        $content = !empty($args[0]) ? $args[0] : "width=device-width, initial-scale=1.0";

        $BlockElement->setAttribute("name", "charset");
        $BlockElement->setAttribute("content", $content);

        return $this->clear($args, $BlockElement);
    }
}