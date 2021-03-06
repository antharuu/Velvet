<?php

namespace Antharuu\Velvet\Elements;

use Antharuu\Velvet\Config;
use Antharuu\Velvet\Parser;
use Antharuu\Velvet\Tools;
use Antharuu\Velvet\Variables;

class HtmlElement
{

    public string $tag = "div";
    public string $subtag = "";
    public string $content = "";

    public array $attributes = [];
    public array $filters = [];
    public array $block = [];

    public bool $keepStrict = false;

    public int $indent = 0;

    public function getHtml($force = false, $noTag = false): string
    {
        if (!$force && get_class($this) === "Antharuu\Velvet\Elements\HtmlElement") {
            if (isset(Config::$elementsPaterns[$this->tag]) ||
                in_array($this->tag, Config::$elementsPaterns)) return $this->paternInit();
        }

        $html = "";

        if ($this->tag !== "|" && !$noTag) {
            $html .= "<$this->tag{$this->getAttributes()}";
            $html .= in_array($this->tag, Config::$selfClose) ? "/>" : ">";
        }

        if (!in_array($this->tag, Config::$selfClose)) {
            $html .= $this->content;
            if (count($this->block) > 0) {
                $P = new Parser();
                $html .= $P->transform(implode("\n", $this->block), $this->indent + 1);
            }

            if ($this->tag !== "|" && !$noTag) $html .= "</$this->tag>";
        }

        return $html;
    }

    private function paternInit(): string
    {
        $class = $this->tag;

        foreach (Config::$elementsPaterns as $v => $p) {
            if (!is_int($p) && $v === $this->tag) $class = $p;
        }

        if (!class_exists($class)) {
            $class = "Antharuu\Velvet\Elements\\" . ucfirst($class) . "Element";
        }

        if (class_exists($class)) {
            $code = $this
                ->castAs($class)
                ->getPatern();
        }

        return (is_string($code)) ? $code : "";
    }

    protected function castAs($newClass)
    {
        $obj = new $newClass;
        foreach (get_object_vars($this) as $key => $name) $obj->$key = $name;
        return $obj;
    }

    private function getAttributes(): string
    {
        $attributes = "";
        ksort($this->attributes);

        foreach ($this->attributes as $attr => $values) {
            $attributes .= " $attr";
            if (!is_null($values) && count($values) > 0) {
                $attributes .= " = \"";
                $vals = implode(' ', $values);
                $attributes .= trim($vals) . "\"";
            }
        }

        return $attributes;
    }

    public
    function setAttribute(string $attributeName, string|array $values = "", string $defaultValue = ""): void
    {
        foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;

        if (is_array($values) && count($values) === 0) $values = $defaultValue;

        if (!is_array($values)) {
            $values = !empty($values) ? $values : $defaultValue;
            $values = (strlen(trim($values)) > 0) ? [$values] : [];
        }


        if (is_array($values)) {
            if (count($values) > 0) {
                foreach ($values as $value) {
                    if (strlen(trim($value)) > 0 && is_string($value)) {
                        $value = $this->removeBraces($value);
                        $this->attributes[$attributeName][$value] = Tools::echo($value);
                    }
                }
            } else $this->attributes[$attributeName] = null;
        }
    }

    private
    function removeBraces(string $value): string
    {
        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}