<?php

namespace Antharuu\Velvet;

use Antharuu\Velvet;
use Antharuu\Velvet\Elements\ExtendsElement;
use Antharuu\Velvet\Elements\HtmlElement;
use Gajus\Dindent\Exception\RuntimeException;
use Gajus\Dindent\Indenter;

class Parser
{
    public static array $blocks = [];
    private string $regex =
        "#^( *)(?'tag'[a-zA-Z\_\-\=|$?]{1}[a-zA-Z0-9\_\-]*)?(:(?'subtag'[a-zA-Z]+))?(?'code'=)?(?'content'.*)$#";
    private string $regexIds =
        "#^(?'ids'\#[a-zA-Z0-9\-\_]+)?(?'content'.*)$#";
    private string $regexClasses =
        "#^(?'classes'\.[a-zA-Z0-9\-\_]+)?(?'content'.*)$#";
    private string $regexAttributes =
        "#^(?'attributes'\((?'attr' *([a-zA-Z0-9\-]+(\=(\\\".*\\\"|\'.*\')|\=\\$\S+)? *)*)\))?(?'content'.*)$#";
    private string $regexSubAttributes =
        "#((?'attribute'[\w-]+)(=(?'value'\"[\w\d\s\v\@\?\!\-\_\\.:\>\<\(\)\~\&\#\{\}\^\$\'\\\"\/]*\"|\'[\w\d\s\v\@\?\!\.\-\_\:\>\<\(\)\~\&\#\{\}\^\$\\\"\\\/']*\')|=\$\S+)?)+#";
    private array $lines = [];
    private array $htmlLines = [];

    public function __construct(
        public Velvet $velvetInstance
    )
    {

    }

    public function transform(string $vlvtCode): string
    {
        $this->lines = explode("\n", $vlvtCode);
        $this->cleanupLines();
        $this->htmlConverter($this->lines);

        $finalHtmlCode = implode("", $this->htmlLines);

        if (Config::$beautify) {
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

        foreach ($this->lines as $line):
            if (!empty(trim($line))):
                $newLines[] = $line;
            endif;
        endforeach;

        $this->lines = $newLines;
    }

    private function htmlConverter(array $lines): void
    {
        $blockIndent = 0;
        $blockElement = null;

        while (isset($lines[0])):
            $line = array_shift($lines);

            $indent = $this->getIndent($line);

            $Block = [];

            while (isset($lines[0]) && $this->getIndent($lines[0]) > $blockIndent):
                $Block[] = substr(array_shift($lines), Config::$indentSize);
            endwhile;

            $blockElement = new HtmlElement();
            $this->htmlBaseBuilder($blockElement, $line);
            $blockElement->block = $Block;

            if (strtolower($blockElement->tag) === "extends") $blockElement = $this->layout($blockElement, $lines);
            else $blockElement = $this->checkCustomTags($blockElement);
            $blockIndent = $indent;

            $this->htmlLines[] = $blockElement->getHtml();
        endwhile;
    }

    private static function getIndent(string $line): int
    {
        return floor((strlen($line) - strlen(ltrim($line))) / Config::$indentSize);
    }

    private function htmlBaseBuilder(HtmlElement $element, $line)
    {
        $line = ltrim($line);

        preg_match_all(str_replace("\n", "", $this->regex), $line, $matches, PREG_SET_ORDER, 0);

        if (!empty(trim($matches[0]['tag']))) $element->tag = trim($matches[0]['tag']) ?? "div";

        $element->content = $matches[0]['content'] ?? "";

        $content = $this->getAttributes($element);
        if (!empty(trim($matches[0]['code']))) $content = $this->getCode($content, $element);
        if (!empty($matches[0]['subtag'])) $element->subtag = $matches[0]['subtag'];

        $element->content = $content;
    }

    private function getAttributes(HtmlElement $element): string
    {
        $content = $element->content;

        $securityIteration = 0;
        while (!str_starts_with($content, " ") && strlen(trim($content)) > 0) {
            preg_match_all($this->regexIds, $content, $matchesIds, PREG_SET_ORDER);
            preg_match_all($this->regexClasses, $content, $matchesClasses, PREG_SET_ORDER);
            preg_match_all($this->regexAttributes, $content, $matchesAttributes, PREG_SET_ORDER);

            if (isset($matchesIds[0]['ids']) && strlen(trim($matchesIds[0]['ids'])) > 0) {
                $element->setAttribute("id", explode("#", $matchesIds[0]['ids']));
                $content = $matchesIds[0]['content'];
            } elseif (isset($matchesClasses[0]['classes']) && strlen(trim($matchesClasses[0]['classes'])) > 0) {
                $element->setAttribute("class", explode(".", $matchesClasses[0]['classes']));
                $content = $matchesClasses[0]['content'];
            } elseif (isset($matchesAttributes[0]['attributes']) && strlen(trim($matchesAttributes[0]['attributes'])) > 0) {
                preg_match_all(str_replace("\n", "", $this->regexSubAttributes), $matchesAttributes[0]['attributes'], $matchesSubAttributes, PREG_SET_ORDER, 0);
                foreach ($matchesSubAttributes as $a) $element->setAttribute($a['attribute'], $a['value'] ?? null);
                $content = $matchesAttributes[0]['content'];
            }

            if ($securityIteration === 1000) dd("Oops an error in the attributes:\n" . $content);
            $securityIteration++;
        }

        return substr($content, 1);
    }

    private function getCode(string $content, HtmlElement $element): string
    {
        $element->content = "";

        array_unshift($element->block, "= " . $content);

        return $element->getHtml(true, true);
    }

    private function layout(HtmlElement $oldElement, array $lines): HtmlElement
    {
        $layoutElement = new ExtendsElement();

        $layoutElement->content = $oldElement->content;
        $layoutElement->block = $lines;

        $content = Velvet::getFile(trim($layoutElement->content), Config::$templatePath);
        $layoutLines = explode("\n", $content);

        $layoutElement->getHtml();
        $newLines = [];
        foreach ($layoutLines as $line) {
            if (str_starts_with(trim($line), "block")) {
                $blockName = explode(" ", trim($line))[1] ?? null;
                if ($blockName !== null && isset(Parser::$blocks[$blockName])) {
                    $indent = self::getIndent($line);
                    foreach (Parser::$blocks[$blockName] as $blockline) {
                        $newLines[] = self::indent($indent) . $blockline;
                    }
                }
            } else {
                $newLines[] = $line;
            }
        }

        $layoutElement->tag = "|";
        $layoutElement->content = "";
        $layoutElement->block = $newLines;

        return $layoutElement;
    }

    private static function indent(int $indent): string
    {
        return str_repeat(str_repeat(" ", Config::$indentSize), $indent);
    }

    private function checkCustomTags(HtmlElement $element): HtmlElement
    {
        foreach (Config::$customTags as $class) {
            $C = new $class($element);
            if ($element->tag === $C->tag) {
                $C->args = explode(" ", Tools::echo($element->content));
                $C->call();
                $C->clear();
                $this->getAttributes($C->element);
            }
        }

        return $element;
    }
}