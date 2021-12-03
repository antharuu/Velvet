<?php

namespace Antharuu\Velvet\Filters;

use Antharuu\Velvet\Elements\HtmlElement;
use Antharuu\Velvet\Interfaces\FilterInterface;
use Antharuu\Velvet\RegexDecoder;
use Exception;

class VelvetFilter implements FilterInterface
{
    public bool $convertElements = true;
    public bool $linesAsContent = true;

    public function applyFilter(HtmlElement $Element): HtmlElement
    {
        $Element->content = $this->filterContent($Element->content);
        $Element->lines = array_map([$this, "filterLines"], $Element->lines);

        return $Element;
    }

    public function filterContent(string $content): string
    {
        return $content;
    }

    public function applyFilterElement(HtmlElement $Element): HtmlElement
    {
        $Element->block = array_map([$this, "filterElements"], $Element->block);

        return $Element;
    }

    public function beforeFiltersElement(HtmlElement $element): HtmlElement
    {
        return $element;
    }

    /**
     * @throws Exception
     */
    public function filterLines(string $line): string
    {
        if (($this->linesAsContent)) {
            $rest = $this->filterContent(RegexDecoder::decode($line)['rest'] ?? "");
            $line = substr($line, 0, -strlen($rest)) . $rest;
        }

        return $line;
    }

    public function filterElements(HtmlElement $element): HtmlElement
    {
        return $element;
    }

}