<?php

namespace Antharuu\Velvet\Elements;

use Antharuu\Velvet;

class IncludeElement extends HtmlElement implements ElementInterface
{
    public function getPatern(): string|bool
    {
        $this->tag = "|";

        $V = new Velvet();

        $this->content = $V->parseFile(trim($this->content));

        return $this->getHtml();
    }
}