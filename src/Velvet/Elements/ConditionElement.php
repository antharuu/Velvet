<?php

namespace Antharuu\Velvet\Elements;

use Antharuu\Velvet\Variables;

class ConditionElement extends HtmlElement implements ElementInterface
{

    public function getPatern(): string|bool
    {
        $condition = trim($this->content);
        foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;

        if (eval("return $condition;")) {
            foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;
            $this->tag = "|";
            $this->content = "";
            return $this->getHtml();
        }

        return "";
    }
}