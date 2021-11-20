<?php

namespace Antharuu\Velvet\Elements;

use Antharuu\Velvet\Variables;

class EchoElement extends HtmlElement implements ElementInterface
{
    public function getPatern(): string|bool
    {
        $code = $this->content;
        if (count($this->block) > 0) $code .= implode("\n", $this->block);

        foreach (Variables::getGlobals() as $var => $value) {
            $$var = $value;
        }

        $code = str_replace("{{", '${&}${{', $code);
        $code = str_replace("}}", '}}${&}$', $code);
        $parts = explode('${&}$', $code);

        $res = "";

        foreach ($parts as $p) {
            if (
                str_starts_with($p, "{{") &&
                str_ends_with($p, "}}")) {
                $p = substr($p, 2, -2);
                $p = eval("return $p;");
            } else {
                $p = eval("return \"$p\";");
            }
            $res .= $p;
        }

        return $res;
    }
}