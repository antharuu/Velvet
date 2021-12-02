<?php

namespace Antharuu\Velvet;

use Exception;

class RegexDecoder
{
    public static array $prefixes = [
        "#" => "id",
        "." => "class",
        "!" => "filter"
    ];
    private static array $parts = [];
    private static string $r_inline = "\|";
    private static string $r_str = "a-zA-Z";
    private static string $r_num = "0-9";
    private static string $r_dash = "\-\_";
    private static string $r_var = "\\$";
    private static string $r_any_double_quote = "\\\"[^\\\"]*\\\"";
    private static string $r_any_simple_quote = "\'[^\']*\'";
    private static string $r_any_echo_mark = "\{\{[^\}\{]*\}\}";
    private static string $r_prefixes = "";
    private static string $r_rest = "(?'rest'.*)";

    /**
     * @throws Exception
     */
    public static function decode(string $line): array
    {
        self::$r_prefixes = "\\" . implode("\\", array_keys(self::$prefixes));
        self::$parts = [];
        $line = self::getTag($line);
        while (!str_starts_with($line, " ") && strlen(trim($line)) > 0) {
            if (!array_key_exists(substr($line, 0, 1),
                array_merge(self::$prefixes, ["$" => ""]))
            ) {
                throw new Exception(
                    "Sorry but \"" . substr($line, 0, 1) . "\"" .
                    " is not recognized by Velvet, please add with \"newShortAttribute\" if needed."
                );
            }

            $line = self::getAttr($line, "vars", "$");
            if (isset(self::$parts["attributes"]["vars"])) $line = self::getAttrVars($line);
            foreach (self::$prefixes as $prefix => $attribute) $line = self::getAttr($line, $attribute, $prefix);
            $line = self::getAttrParenthesis($line);
        }
        self::$parts['rest'] = substr($line, 1);
        return self::$parts;
    }

    private static function getTag(string $line): string
    {
        $r = self::regexMaker([
            "tag" => [
                "[" . self::r("inline", "str") . "]",
                "[" . self::r("str", "num", "dash") . "]*",
                "suffix" => "?"
            ],
        ], true);
        $matches = self::getMatches($r, $line);

        self::$parts['tag'] = (isset($matches['tag']) && !empty($matches['tag'])) ? $matches['tag'] : "div";

        return (isset($matches['rest']) && !empty($matches['rest'])) ? $matches['rest'] : "";
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
                "[" . self::r("attributes", "str") . "]",
                "[" . self::r("str", "num", "dash") . "]*",
                ")*",
                "suffix" => "?"
            ],
        ], true);

        $matches = self::getMatches($r, $line);

        if (isset($matches[$name])) {
            self::addAttribute($name, implode(" ", self::arrayValue($matches[$name], $prefix)));
        }

        return $matches['rest'] ?? "";
    }

    private static function addAttribute(string $attribute, string $value, bool $force = false): void
    {
        if ($force || !empty(trim($value))) self::$parts["attributes"][$attribute][] = trim($value);
    }

    private static function arrayValue(string $values, string $separator): array
    {
        return explode($separator, substr($values, strlen($separator)));
    }

    private static function getAttrVars(string $line): string
    {
        foreach (self::$parts['attributes']['vars'] as $varName) {
            $value = Variable::get($varName);
            if (is_string($value)) $line = $value . $line;
            elseif (is_array($value)) {
                foreach ($value as $k => $val) {
                    if (is_string($val)) self::addAttribute($k, $val);
                    elseif (is_array($val)) {
                        foreach ($val as $v) if (is_string($v)) self::addAttribute($k, $v);
                    }
                }
            }
        }
        unset(self::$parts['attributes']['vars']);
        return $line;
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
            self::addAttribute($attribute, $value, (strlen($attribute) > 0));
        }
    }
}