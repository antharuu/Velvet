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
    public static array $ifs = [];
    private string $regex =
        "#^( *)(?'tag'[a-zA-Z\_\-\=|$?]{1}[a-zA-Z0-9\_\-]*)?(:(?'subtag'[a-zA-Z]+))?(?'code'=)?(?'content'.*)$#";
    private string $regexIds =
        "#^(?'ids'\#[a-zA-Z0-9\-\_]+)?(?'content'.*)$#";
    private string $regexClasses =
        "#^(?'classes'\.[a-zA-Z0-9\-\_]+)?(?'content'.*)$#";
    private string $regexFilters =
        "#^(?'filters'\![a-zA-Z0-9\-\_]+)?(?'content'.*)$#";
    private string $regexAttributes =
        "#^(?'attributes'\((?'attr' *([a-zA-Z0-9\-]+(\=(\\\".*\\\"|\'.*\')|\=\\$\S+)? *)*)\))?(?'content'.*)$#";
    private string $regexSubAttributes =
        "#((?'attribute'[\w-]+)(=(?'value'\"[\w\d\s\v\@\?\!\-\_\\.:\>\<\(\)\~\&\#\{\}\^\$\'\\\"\/]*\"|\'[\w\d\s\v\@\?\!\.\-\_\:\>\<\(\)\~\&\#\{\}\^\$\\\"\\\/']*\')|=\$\S+)?)+#";
    private array $lines = [];
    private array $htmlLines = [];

    public function __construct(
        public ?Velvet $velvetInstance = null
    )
    {

    }

    public function transform(string $vlvtCode, $indent = 0): string
    {
        $this->lines = explode("\n", $vlvtCode);
        $this->cleanupLines();
        $this->htmlConverter($this->lines, $indent);

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

    public function htmlConverter(array $lines, int $blockIndent = 0): void
    {
        $blockElement = null;

        while (isset($lines[0])):
            $line = array_shift($lines);

            $Block = [];

            while (isset($lines[0]) && $this->getIndent($lines[0], $blockIndent) > $blockIndent):
                $Block[] = substr(array_shift($lines), Config::$indentSize);
            endwhile;

            $blockElement = new HtmlElement();
            $this->htmlBaseBuilder($blockElement, $line);
            $blockElement->block = $Block;

            $this->cleanIfs($blockIndent);

            if (strtolower($blockElement->tag) === "extends") $blockElement = $this->layout($blockElement, $lines);
            else {
                $blockElement = $this->inlineNesting($blockElement);
                $blockElement = $this->checkCustomTags($blockElement);
                $blockElement = $this->applyFilters($blockElement);
                $blockElement->indent = $blockIndent;
            }

            $this->htmlLines[] = $blockElement->getHtml();
        endwhile;
    }

    private static function getIndent(string $line, int $blockIndent = 0): int
    {
        return (floor((strlen($line) - strlen(ltrim($line))) / Config::$indentSize)) + $blockIndent;
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
            preg_match_all($this->regexAttributes, $content, $matchesAttributes, PREG_SET_ORDER);

            if (str_starts_with($content, "$")) $content = Tools::echo($content);

            if ($this->regexHasAttr($this->regexIds, "ids", $content)) {
                $matches = $this->regexGetAttr($this->regexIds, "ids", $content);
                $element->setAttribute("id", explode("#", $matches['ids']));
                $content = $matches['content'];
            } elseif ($this->regexHasAttr($this->regexClasses, "classes", $content)) {
                $matches = $this->regexGetAttr($this->regexClasses, "classes", $content);
                $element->setAttribute("class", explode(".", $matches['classes']));
                $content = $matches['content'];
            } elseif ($this->regexHasAttr($this->regexFilters, "filters", $content)) {
                $matches = $this->regexGetAttr($this->regexFilters, "filters", $content);
                $element->filters[] = substr($matches['filters'], 1);
                $content = $matches['content'];
            } elseif ($this->regexHasAttr($this->regexAttributes, "attributes", $content)) {
                preg_match_all(str_replace("\n", "", $this->regexSubAttributes), $matchesAttributes[0]['attributes'], $matchesSubAttributes, PREG_SET_ORDER, 0);
                foreach ($matchesSubAttributes as $a) $element->setAttribute($a['attribute'], $a['value'] ?? null);
                $content = $matchesAttributes[0]['content'];
            }

            if ($securityIteration === 1000) dd("Oops an error in the attributes: " . $content);
            $securityIteration++;
        }

        return substr($content, 1);
    }

    private function regexHasAttr(string $regex, string $string, string $content): bool
    {
        preg_match_all($regex, $content, $matches, PREG_SET_ORDER);
        return isset($matches[0][$string]) && strlen(trim($matches[0][$string])) > 0;
    }

    private function regexGetAttr(string $regex, string $string, mixed $content): array
    {
        preg_match_all($regex, $content, $matches, PREG_SET_ORDER);
        return $matches[0];
    }

    private function getCode(string $content, HtmlElement $element): string
    {
        $element->content = "";

        array_unshift($element->block, "= " . $content);

        return $element->getHtml(true, true);
    }

    private function cleanIfs(int $blockIndent)
    {
        $newIfs = [];
        foreach (self::$ifs as $indent => $if) if ($indent <= $blockIndent) $newIfs[$indent] = $if;
        self::$ifs = $newIfs;
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
                    foreach (Parser::$blocks[$blockName] as $blockLine) {
                        $newLines[] = self::indent($indent) . $blockLine;
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

    private function inlineNesting(HtmlElement $blockElement): HtmlElement
    {
        if (!empty(trim($blockElement->content)) && str_starts_with(trim($blockElement->content), ">")) {
            $newBlock = [];
            foreach ($blockElement->block as $b) $newBlock[] = Parser::indent(1) . $b;
            array_unshift($newBlock, substr(ltrim($blockElement->content), 1));
            $blockElement->content = "";
            $blockElement->block = $newBlock;
        }

        return $blockElement;
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

    private function applyFilters(HtmlElement $element): HtmlElement
    {
        foreach ($element->filters as $filter) {
            foreach (Config::$filters as $class) {
                $C = new $class($element);
                if (strtolower($filter) === strtolower($C->filterName)) {
                    $C->filter();
                }
            }
        }
        return $element;
    }
}