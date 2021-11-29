<?php

namespace Antharuu\Velvet;

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
}