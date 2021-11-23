<?php

namespace Antharuu\Velvet\Elements;

use Antharuu\Velvet;

class IncludeElement extends HtmlElement implements ElementInterface
{
    public function getPatern(): string|bool
    {
        $this->tag = "|";

        $V = new Velvet();

        $this->content = $V->parse_file(trim($this->content));

        return $this->getHtml();
    }
}