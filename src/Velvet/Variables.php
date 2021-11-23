<?php

namespace Antharuu\Velvet;

class Variables
{
    public static function setGlobals(array $variables)
    {
        $namesVars = array_keys($variables);

        foreach ($namesVars as $var) {
            if ($var !== "code") {
                $GLOBALS['Velvet'][$var] = $variables[$var];
            }
        }
    }

    public static function getGlobals(): array
    {
        return $GLOBALS['Velvet'] ?? [];
    }
}