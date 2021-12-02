<?php

namespace Antharuu\Velvet\Filters;

use Antharuu\Velvet\Elements\HtmlElement;
use Antharuu\Velvet\Interfaces\FilterInterface;
use Michelf\Markdown;

class MarkdownFilter extends VelvetFilter implements FilterInterface
{
    public bool $convertElements = false;

    public function beforeFiltersElement(HtmlElement $element): HtmlElement
    {
        $element->tag = "|";
        $Content = $element->content . "\n" . implode("\n", $element->lines);

        $M = new Markdown();
        $element->content = $M->transform($Content);

        return $element;
    }
}