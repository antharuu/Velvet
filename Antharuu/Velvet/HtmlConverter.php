<?php

namespace Antharuu\Velvet;

use Antharuu\Velvet;
use Antharuu\Velvet\Elements\HtmlElement;
use JetBrains\PhpStorm\Pure;

class HtmlConverter
{
    private static array $inlineElements = [
        "a", "abbr", "acronym", "audio",
        "b", "bdi", "bdo", "big", "br", "button",
        "canvas", "cite", "code",
        "data", "datalist", "del", "dfn",
        "em", "embed",
        "h1", "h2", "h3", "h3", "h4", "h5", "h6",
        "i", "iframe", "img", "input", "ins",
        "kbd",
        "label",
        "map", "mark", "meter",
        "noscript",
        "object", "output",
        "p", "picture", "progress",
        "q",
        "ruby",
        "s", "samp", "script", "select", "slot", "small", "span", "strong", "sub", "sup", "svg",
        "template", "u", "tt", "var", "video", "wbr"
    ];

    private static function content(HtmlElement $element): string
    {
        $subElements = [];
        foreach ($element->block as $subElement) $subElements[] = HtmlConverter::convert($subElement);
        return $element->content . implode("", $subElements);
    }

    public static function convert(HtmlElement $element): string
    {
        $element->indent -= 1;
        $element->isInline = in_array($element->tag, self::$inlineElements);
        foreach ($element->block as $e) $e->isParentInline = $element->isInline;
        $element->isEmpty = !self::hasContent($element);
        $Html = self::tag($element);
        $Html .= self::content($element);
        $Html .= self::tag($element, false, str_ends_with($Html, "\n"));
        return $Html;
    }

    private static function hasContent(HtmlElement $element): bool
    {
        return !empty(trim($element->content)) || count($element->block) > 0;
    }

    private static function tag(HtmlElement $element, bool $open = true, bool $noNl = false): string
    {
        if ($element->tag === "|") return "";
        if (
            (!Velvet::getSettings()["minimize"] ?? true) &&
            !(!$open && $element->isInline) &&
            !($open && $element->isParentInline) &&
            !(!$open && $element->isEmpty)) {

            if ($open) {
                return self::indent($element) .
                    "<" . $element->tag . self::Attributes($element) . ">" .
                    self::newLine($element);
            } else {
                return ($noNl ? "" : self::newLine($element)) .
                    self::indent($element) . "</" . $element->tag . ">" .
                    self::newLine($element);
            }

        } else {
            if ($open) return "<" . $element->tag . self::Attributes($element) . ">";
            else return "</" . $element->tag . ">";
        }
    }

    #[Pure] private static function indent(HtmlElement $element, int $plus = 0): string
    {
        $indent = ($element->indent ?? 0) + $plus;
        return str_repeat(str_repeat(" ", Velvet::getSettings()['indent_size']), $indent);
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

    #[Pure] private static function newLine(HtmlElement $element): string
    {
        return !$element->isEmpty && !$element->isInline ? "\n" : "";
    }
}