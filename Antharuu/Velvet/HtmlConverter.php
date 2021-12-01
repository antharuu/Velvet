<?php

namespace Antharuu\Velvet;

use Antharuu\Velvet\Elements\HtmlElement;

class HtmlConverter
{
    public static function convert(HtmlElement $element): string
    {
        $Html = self::tag($element);
        $Html .= self::content($element);
        $Html .= self::tag($element, false);
        return $Html;
    }

    private static function tag(HtmlElement $element, bool $open = true): string
    {
        if ($open) return "<" . $element->tag . self::Attributes($element) . ">";
        else return "</" . $element->tag . ">";
    }

    private static function Attributes(HtmlElement $element): string
    {
        $attributes = [];
        ksort($element->attributes, SORT_DESC);
        foreach ($element->attributes as $attribute => $values) {
            if (count($values) === 1 && empty($values[0])) $attributes[] = "$attribute";
            else $attributes[] = "$attribute=\"" . implode(" ", $values) . "\"";
        }
        $attributes = implode(" ", $attributes);
        return strlen(trim($attributes)) > 0 ? " $attributes" : "";
    }

    private static function content(HtmlElement $element): string
    {
        $subElements = [];
        foreach ($element->block as $subElement) $subElements[] = HtmlConverter::convert($subElement);
        return $element->content . implode("", $subElements);
    }
}