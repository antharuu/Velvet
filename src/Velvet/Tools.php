<?php

namespace Antharuu\Velvet;

class Tools
{
    public static function echo(string $code): string
    {
        foreach (Variables::getGlobals() as $var => $value) $$var = $value;

        $code = str_replace("{{", '${&}${{', $code);
        $code = str_replace("}}", '}}${&}$', $code);
        $parts = explode('${&}$', $code);

        $res = "";

        foreach ($parts as $p) {
            if (
                str_starts_with($p, "{{") &&
                str_ends_with($p, "}}")) {
                $p = substr($p, 2, -2);
                $p = str_replace("\"\"", "\"", $p);
                $p = str_replace("''", "'", $p);
                $p = eval("return $p;");
            } else {
                $p = eval("return \"$p\";");
            }
            $res .= $p;
        }

        return $res;
    }
}