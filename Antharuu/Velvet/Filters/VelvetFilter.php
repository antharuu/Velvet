<?php

namespace Antharuu\Velvet\Filters;

use Antharuu\Velvet\Elements\HtmlElement;
use Antharuu\Velvet\Interfaces\FilterInterface;

class VelvetFilter implements FilterInterface
{
    public bool $convertElements = true;
    public bool $linesAsContent = true;

    public function applyFilter(HtmlElement $Element): HtmlElement
    {
        $Element->content = $this->filterContent($Element->content);
        $Element->lines = array_map([$this, "filterLines"], $Element->lines);
        $Element->block = array_map([$this, "filterElements"], $Element->block);

        return $Element;
    }

    public function filterContent(string $content): string
    {
        return $content;
    }

    public function beforeFiltersElement(HtmlElement $element): HtmlElement
    {
        return $element;
    }

    public function filterLines(string $line): string
    {
        return ($this->linesAsContent) ? $this->filterContent($line) : $line;
    }

    public function filterElements(HtmlElement $element): HtmlElement
    {
        return $element;
    }

}