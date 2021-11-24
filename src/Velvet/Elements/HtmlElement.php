<?php

namespace Antharuu\Velvet\Elements;

use Antharuu\Velvet;
use Antharuu\Velvet\Tools;
use Antharuu\Velvet\Variables;

class HtmlElement
{

    public string $tag = "div";
    public string $subtag = "";
    public string $content = "";
    public int $indent = 0;
    public array $attributes = [];
    public array $filters = [];
    public array $block = [];
    public array $selfClose = [
        "!DOCTYPE",
        "area",
        "base",
        "br",
        "col",
        "embed",
        "hr",
        "img",
        "input",
        "link",
        "meta",
        "param",
        "source",
        "track",
        "wbr",
        "command",
        "keygen",
        "menuitem"
    ];
    public array $paterns = [
        "extends",
        "block",
        "include",
        "code" => "?",
        "echo" => "=",
        "for",
        "condition" => "if"
    ];

    public function getHtml($force = false, $noTag = false): string
    {

        if (!$force) {
            if (get_class($this) === "Antharuu\Velvet\Elements\HtmlElement") {
                if (in_array($this->tag, $this->paterns)) return $this->paternInit();
            }
        }

        $html = "";

        if ($this->tag !== "|" && !$noTag) {
            $html .= "<{$this->tag}{$this->getAttributes()}";
            $html .= in_array($this->tag, $this->selfClose) ? "/>" : ">";
        }

        if (!in_array($this->tag, $this->selfClose)) {
            $html .= $this->content;
            if (count($this->block) > 0) {
                $P = new Velvet();
                $html .= $P->parse(implode("\n", $this->block));
            }

            if ($this->tag !== "|" && !$noTag) $html .= "</{$this->tag}>";
        }

        return $html;
    }

    private function paternInit(): string
    {
        $class = $this->tag;
        foreach ($this->paterns as $p => $v) {
            if (!is_int($p) && $v === $this->tag) $class = $p;
        }

        $code = $this
            ->castAs("Antharuu\Velvet\Elements\\" . ucfirst($class) . "Element")
            ->getPatern();
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
                $attributes .= "=\"";
                $vals = implode(' ', $values);
                $attributes .= trim($vals) . "\"";
            }
        }

        return $attributes;
    }

    public function setAttribute(string $attributeName, string|array $values = "", string $defaultValue = ""): void
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

    private function removeBraces(string $value): string
    {
        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}