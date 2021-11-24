<?php

namespace Antharuu\Velvet\Elements;

use Antharuu\Velvet\Parser;
use Antharuu\Velvet\Variables;

class ConditionElement extends HtmlElement implements ElementInterface
{

    public function getPatern(): string|bool
    {
        $condition = trim($this->content);

        if ($this->tag === "if") return $this->ifCond($condition);
        if ($this->tag === "elseif" ||
            $this->tag === "else if") return $this->elseIfCond($condition);
        if ($this->tag === "else") return $this->elseCond();

        return "";
    }

    private function ifCond(string $condition): string
    {
        foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;

        $cond = eval("return $condition;");

        Parser::$ifs[$this->indent] = is_bool($cond) ? $cond : false;

        if (Parser::$ifs[$this->indent]) return $this->getBlockContent();

        return "";
    }

    private function getBlockContent(): string
    {
        foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;
        $this->tag = "|";
        $this->content = "";
        return $this->getHtml();
    }

    private function elseIfCond(string $condition): string
    {
        if (isset(Parser::$ifs[$this->indent]) && !Parser::$ifs[$this->indent]) return $this->ifCond($condition);
        return "";
    }

    private function elseCond(): string
    {
        if (isset(Parser::$ifs[$this->indent]) && !Parser::$ifs[$this->indent]) return $this->getBlockContent();
        return "";
    }
}