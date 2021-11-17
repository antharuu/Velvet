<?php

namespace Antharuu\Velvet\Elements;

use JetBrains\PhpStorm\Pure;

class HtmlElement
{

    public string $tag = "div";
    public string $content = "";
    public int $indent = 0;
    public array $attributes = [];

    #[Pure] public function getHtml(): string
    {
        return $this->indent()
            . "<{$this->tag}{$this->getAttributes()}>"
            . $this->content
            . "</{$this->tag}>";
    }

    private function indent(int $addedIndent = 0): string
    {
        return str_repeat("    ", $this->indent + $addedIndent);
    }

    public function setAttribute(string $attributeName, string|array|null $values): void
    {
        if ($values !== null) {
            if (!is_array($values)) {
                if (strlen(trim($values)) > 0) $values = [$values];
            }

            if (is_array($values)) {
                foreach ($values as $value) {
                    if (strlen(trim($value)) > 0 && is_string($value)) {
                        $this->attributes[$attributeName][] = $value;
                    }
                }
            }
        }
    }

    private function getAttributes(): string
    {
        $Attributes = "";

        foreach ($this->attributes as $attr => $values) {
            if (count($values) > 0) {
                $Attributes .= " $attr=\"";
                $vals = implode(' ', $values);
                $Attributes .= trim($vals) . "\"";
            }
        }

        return $Attributes;
    }
}