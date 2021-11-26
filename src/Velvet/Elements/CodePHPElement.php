<?php

namespace Antharuu\Velvet\Elements;

use Antharuu\Velvet\Variables;

class CodePHPElement extends HtmlElement implements ElementInterface
{
    public function getPatern(): string|bool
    {
        $code = $this->content;
        if (count($this->block) > 0) $code .= implode("\n", $this->block);

        foreach (Variables::getGlobals() as $var => $value) {
            $$var = $value;
        }

        ob_start();
        eval($code . ";");
        Variables::setGlobals(get_defined_vars());
        return ob_get_clean();
    }
}