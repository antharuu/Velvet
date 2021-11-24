<?php

namespace Antharuu\Velvet\Filters;

use Antharuu\Velvet\Elements\HtmlElement;

abstract class Filter implements FiltersInterface
{
    public string $filterName = "";

    public function __construct(
        public HtmlElement $element
    )
    {
        $this->filterName = $this->register();
    }

    public function filter()
    {
        $this->element->content = $this->filterContent($this->element->content);
        $newBlock = [];
        foreach ($this->element->block as $line) $newBlock[] = $this->filterEachBlockLines($line);
        $this->element->block = $newBlock;
    }
}