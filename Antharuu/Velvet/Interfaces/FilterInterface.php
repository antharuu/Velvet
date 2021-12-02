<?php

namespace Antharuu\Velvet\Interfaces;

use Antharuu\Velvet\Elements\HtmlElement;

interface FilterInterface
{
    public function filterContent(string $content): string;

    public function filterLines(string $line): string;

    public function filterElements(HtmlElement $element): HtmlElement;

    public function beforeFiltersElement(HtmlElement $element): HtmlElement;
}