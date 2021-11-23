<?php

namespace Antharuu\Velvet\Elements;

use Antharuu\Velvet\Tools;

class EchoElement extends HtmlElement implements ElementInterface
{
    public function getPatern(): string|bool
    {
        $code = $this->content;
        if (count($this->block) > 0) $code .= implode("\n", $this->block);
        return Tools::echo($code);
    }
}