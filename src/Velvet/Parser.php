<?php

namespace Antharuu\Velvet;

use Antharuu\Velvet;
use Antharuu\Velvet\Elements\HtmlElement;
use ErrorException;
use Gajus\Dindent\Exception\RuntimeException;
use Gajus\Dindent\Indenter;

class Parser
{
    private string $Regex =
        "#^( *)(?'tag'[a-zA-Z\_\-\=|$?]{1}[a-zA-Z0-9\_\-]*)?(?'code'=)?(?'content'.*)$#";
    private string $RegexIds =
        "#^(?'ids'\#[a-zA-Z0-9\-\_]+)?(?'content'.*)$#";
    private string $RegexClasses =
        "#^(?'classes'\.[a-zA-Z0-9\-\_]+)?(?'content'.*)$#";
    private string $RegexAttributes =
        "#^(?'attributes'\((?'attr' *([a-zA-Z0-9\-]+(\=(\\\".*\\\"|\'.*\')|\=\\$\S+)? *)*)\))?(?'content'.*)$#";
    private string $RegexSubAttributes =
        "#((?'attribute'[\w-]+)(=(?'value'\"[\w\d\s\v\@\?\!\-\_\\.:\>\<\(\)\~\&\#\{\}\^\$\'\\\"\/]*\"|\'[\w\d\s\v\@\?\!\.\-\_\:\>\<\(\)\~\&\#\{\}\^\$\\\"\\\/']*\')|=\$\S+)?)+#";

    private array $Lines = [];
    private array $HtmlLines = [];

    public function __construct(
        public Velvet $VelvetInstance
    )
    {

    }

    public function transform(string $vlvtCode): string
    {
        $this->Lines = explode("\n", $vlvtCode);
        $this->cleanupLines();
        $this->htmlConverter($this->Lines);

        $finalHtmlCode = implode("", $this->HtmlLines);

        if (Config::$Beautify) {
            $indenter = new Indenter();
            try {
                $finalHtmlCode = str_replace("\n\n", "\n", $indenter->indent($finalHtmlCode));
            } catch (RuntimeException $e) {
                die($e);
            }
        }
        return $finalHtmlCode;
    }

    private function cleanupLines()
    {
        $newLines = [];

        foreach ($this->Lines as $line):
            if (!empty(trim($line))):
                $newLines[] = $line;
            endif;
        endforeach;

        $this->Lines = $newLines;
    }

    private function htmlConverter(array $Lines): void
    {
        $BlockIndent = 0;
        $BlockElement = null;

        while (isset($Lines[0])):
            $line = array_shift($Lines);

            $indent = $this->get_indent($line);

            $Block = [];

            while (isset($Lines[0]) && $this->get_indent($Lines[0]) > $BlockIndent):
                $Block[] = substr(array_shift($Lines), Config::$indent_size);
            endwhile;

            $BlockElement = new HtmlElement();
            $this->HtmlBaseBuilder($BlockElement, $line);
            $BlockElement->block = $Block;

            $BlockElement = $this->checkCustomTags($BlockElement);

            $BlockIndent = $indent;

            $this->HtmlLines[] = $BlockElement->getHtml();
        endwhile;
    }

    private function get_indent(string $line): int
    {
        return floor((strlen($line) - strlen(ltrim($line))) / Config::$indent_size);
    }

    private function HtmlBaseBuilder(HtmlElement $Element, $line)
    {
        $line = ltrim($line);

        $res = preg_match_all(str_replace("\n", "", $this->Regex), $line, $matches, PREG_SET_ORDER, 0);

        if (!empty(trim($matches[0]['tag']))) $Element->tag = trim($matches[0]['tag']) ?? "div";

        $Content = $matches[0]['content'] ?? "";


        try {
            $Content = $this->getAttributes($Content, $Element);
            if (!empty(trim($matches[0]['code']))) $Content = $this->getCode($Content, $Element);
        } catch (ErrorException $e) {
            die("<pre><code>$e</code></pre>");
        }

        $Element->content = $Content;
    }

    private function getAttributes(string $Content, HtmlElement $Element): string
    {
        $securityIteration = 0;
        while (!str_starts_with($Content, " ") && strlen(trim($Content)) > 0) {
            preg_match_all(str_replace("\n", "", $this->RegexIds), $Content, $matchesIds, PREG_SET_ORDER, 0);
            preg_match_all(str_replace("\n", "", $this->RegexClasses), $Content, $matchesClasses, PREG_SET_ORDER, 0);
            preg_match_all(str_replace("\n", "", $this->RegexAttributes), $Content, $matchesAttributes, PREG_SET_ORDER, 0);

            if (isset($matchesIds[0]['ids']) && strlen(trim($matchesIds[0]['ids'])) > 0) {
                $Element->setAttribute("id", explode("#", $matchesIds[0]['ids']));
                $Content = $matchesIds[0]['content'];
            } elseif (isset($matchesClasses[0]['classes']) && strlen(trim($matchesClasses[0]['classes'])) > 0) {
                $Element->setAttribute("class", explode(".", $matchesClasses[0]['classes']));
                $Content = $matchesClasses[0]['content'];
            } elseif (isset($matchesAttributes[0]['attributes']) && strlen(trim($matchesAttributes[0]['attributes'])) > 0) {
                preg_match_all(str_replace("\n", "", $this->RegexSubAttributes), $matchesAttributes[0]['attributes'], $matchesSubAttributes, PREG_SET_ORDER, 0);
                foreach ($matchesSubAttributes as $A) $Element->setAttribute($A['attribute'], $A['value'] ?? null);
                $Content = $matchesAttributes[0]['content'];
            }

            if ($securityIteration === 1000) dd("This tag is unknown:\n" . $Content);
            $securityIteration++;
        }

        return substr($Content, 1);
    }

    private function getCode(string $Content, HtmlElement $Element): string
    {
        $Element->content = "";

        array_unshift($Element->block, "= " . $Content);

        return $Element->getHtml(true, true);
    }

    private function checkCustomTags(HtmlElement $BlockElement): HtmlElement
    {
        foreach (Config::$CustomTags as $class) {
            $C = new $class;
            if ($BlockElement->tag === $C->tag) {
                $args = explode(" ", Tools::echo($BlockElement->content));
                $BlockElement = $C->call($args, $BlockElement);
                $this->getAttributes($BlockElement->content, $BlockElement);
            }
        }

        return $BlockElement;
    }
}