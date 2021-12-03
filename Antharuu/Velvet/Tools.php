<?php

namespace Antharuu\Velvet;

use Antharuu\Velvet;
use Exception;

class Tools
{

    public static function cleanString(
        string $string,
        string $startCharacter = '"',
        string $endCharacter = null
    ): string
    {
        $endCharacter = $endCharacter ?? $startCharacter;

        return (str_starts_with($string, $startCharacter)
            && str_ends_with($string, $endCharacter)) ? substr($string,
            strlen($startCharacter), 0 - strlen($endCharacter)) : $string;
    }

    public static function cleanArray($array): array
    {
        $newArray = [];
        foreach ($array as $k => $match) if (is_string($k)) $newArray[$k] = $match;
        return $newArray;
    }

    /**
     * @throws Exception
     */
    public static function echoContent(array $parts): array
    {
        $parts['rest'] =
            self::echoParse($parts['rest'], ($parts['echo'] ?? false));

        return $parts;
    }

    private static function echoParse(string $string, bool $echoAll = false): string
    {
        $string = str_replace("{{", Velvet::$separator . "{{", $string);
        $string = str_replace("}}", "}}" . Velvet::$separator, $string);
        $partsString = explode(Velvet::$separator, $string);

        $returned = [];
        foreach ($partsString as $str) {
            if (str_starts_with($str, "{{") && str_ends_with($str, "}}")) $returned[] =
                self::echo(substr($str, 2, -2), false);
            else $returned[] = $echoAll ? self::echo($str) : $str;
        }
        return implode("", $returned);
    }

    private static function echo(string $string, bool $quoted = true): string
    {
        foreach (Variable::getAll() as $variableName => $variableValue) $$variableName = $variableValue;
        return $quoted ? eval("return \"$string\";") : eval("return $string;");
    }

    public static function execute(string $executableCode): void
    {
        eval("return $executableCode;");
        unset($executableCode);
        Variable::addMultiple(get_defined_vars());
    }
}