<?php

namespace Antharuu\Velvet\Elements;

use Antharuu\Velvet\Variables;

class CodeElement extends HtmlElement implements ElementInterface
{
    public function getPatern(): string|bool
    {
        $code = $this->content;
        if (count($this->block) > 0) $code .= implode("\n", $this->block);

        ob_start();
        eval($code . ";");
        Variables::setGlobals(get_defined_vars());
        return ob_get_clean();
    }
}