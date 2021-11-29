<?php

namespace Antharuu\Velvet;

class RegexDecoder
{
    private static array $parts = [];

    private static string $r_str = "a-zA-Z";
    private static string $r_num = "0-9";
    private static string $r_dash = "\-\_";
    private static string $r_var = "\\$";
    private static string $r_any_double_quote = "\\\"[^\\\"]*\\\"";
    private static string $r_any_simple_quote = "\'[^\']*\'";
    private static string $r_any_echo_mark = "\{\{[^\}\{]*\}\}";
    private static string $r_rest = "(?'rest'.*)";

    public static function decode(string $line): array
    {
        self::$parts = [];
        $line = self::getTag($line);
        while (!str_starts_with($line, " ")) {
            $line = self::getAttr($line, "id", "#");
            $line = self::getAttr($line, "class", ".");
            $line = self::getAttr($line, "filter", "!");
            $line = self::getAttrParenthesis($line);
        }
        self::$parts['rest'] = substr($line, 1);
        return self::$parts;
    }

    private static function getTag(string $line): string
    {
        $r = self::regexMaker([
            "tag" => [
                "[" . self::r("str") . "]",
                "[" . self::r("str", "num", "dash") . "]*"
            ],
        ], true);
        $matches = self::getMatches($r, $line);

        self::$parts['tag'] = $matches['tag'];

        return $matches['rest'] ?? "";
    }

    private static function regexMaker(
        array  $regex,
        bool   $rest = false,
        string $prefix = "^",
        bool   $surround = true
    ): string
    {
        $reg = self::regexGroupMaker($regex);
        $surround = $surround ? "#" : "";
        return $surround . $prefix . $reg . ($rest ? self::$r_rest : "") . $surround;
    }

    private static function regexGroupMaker(array $regex): string
    {
        $reg = "";
        foreach ($regex as $group => $values) {
            $prefix = $values['prefix'] ?? "";
            $suffix = $values['suffix'] ?? "";
            unset($values['prefix']);
            unset($values['suffix']);
            $reg .= "$prefix(?'$group'" . implode($values) . ")$suffix";
        }
        return $reg;
    }

    private static function r(...$r): string
    {
        $result = "";

        foreach ($r as $varName) {
            $varName = "r_" . $varName;
            $result .= self::$$varName ?? $varName;
        }

        return $result;
    }

    private static function getMatches(string $r, string $line): array
    {
        preg_match($r, $line, $matches);
        return Tools::cleanArray($matches);
    }

    private static function getAttr(string $line, $name, $prefix): string
    {
        $r = self::regexMaker([
            $name => [
                "(\\$prefix",
                "[" . self::r("str") . "]",
                "[" . self::r("str", "num", "dash") . "]*",
                ")*",
                "suffix" => "?"
            ],
        ], true);

        $matches = self::getMatches($r, $line);

        self::addAttribute($name, implode(" ", self::arrayValue($matches[$name], $prefix)));

        return $matches['rest'] ?? "";
    }

    private static function addAttribute($attribute, $value): void
    {
        $oldValue = self::$parts["attributes"][$attribute] ?? "";
        if (!empty($oldValue) && !empty($value)) $oldValue .= " ";
        self::$parts["attributes"][$attribute] = $oldValue . $value;
    }

    private static function arrayValue(string $values, string $separator): array
    {
        return explode($separator, substr($values, strlen($separator)));
    }

    private static function getAttrParenthesis(string $line)
    {
        $r = self::regexMaker([
            "attributes" => [
                "\(",
                "[^\)]*",
                "\)",
                "suffix" => "?"
            ],
        ], true);

        $matches = self::getMatches($r, $line);
        if (!empty($matches["attributes"])) self::getAttrParenthesisValues($matches["attributes"]);
        return $matches['rest'] ?? "";
    }

    private static function getAttrParenthesisValues(string $attributes): void
    {
        $attributes = Tools::cleanString($attributes, "(", ")");

        $r = self::regexMaker([
            "attribute" => [
                self::regexMaker([
                    "name" => [
                        "[" . self::r("str", "num", "dash", "var") . "]+",
                        self::regexMaker([
                            "val" => [
                                "=",
                                self::regexMaker([
                                    "value" => [
                                        "([" . self::r("str", "num", "dash", "var") . "]+)",
                                        "|(" . self::r("any_double_quote") . ")",
                                        "|(" . self::r("any_simple_quote") . ")",
                                        "|(" . self::r("any_echo_mark") . ")",
                                    ],
                                ], false, "", false)
                            ],
                        ], false, "", false),
                        '?'
                    ],
                ], false, "", false)
            ],
        ], false, "");

        preg_match_all($r, $attributes, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $match = Tools::cleanArray($match);
            $attribute = explode("=", $match['name'])[0];
            $value = $match['value'] ?? "";
            $value = Tools::cleanString($value);
            $value = Tools::cleanString($value, "'");
            self::addAttribute($attribute, $value);
        }
    }
}