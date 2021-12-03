<?php

namespace Antharuu;

use Antharuu\Velvet\Elements\HtmlElement;
use Antharuu\Velvet\Filters\LowerFilter;
use Antharuu\Velvet\Filters\MarkdownFilter;
use Antharuu\Velvet\Filters\UpperFilter;
use Antharuu\Velvet\Filters\VelvetFilter;
use Antharuu\Velvet\HtmlConverter;
use Antharuu\Velvet\RegexDecoder;
use Antharuu\Velvet\Tools;
use Antharuu\Velvet\Variable;
use Exception;

class Velvet
{
    public static string $separator = "#!:%~|~%:!#";
    protected static array $settings = [
        "default_path" => "views",
        "layout_path" => "layout",
        "used_extensions" => ["vlvt", "velvet"],
        "indent_size" => 4,
        "minimize" => false
    ];
    private static array $filters = [
        "upper" => UpperFilter::class,
        "lower" => LowerFilter::class,
        "md" => MarkdownFilter::class,
        "markdown" => MarkdownFilter::class,
    ];

    public function __construct(
        array $variables = [],
        array $settings = []
    )
    {
        foreach ($variables as $variableName => $value) Variable::add($variableName, $value);
        self::$settings = array_merge(self::$settings, $settings);
    }

    /**
     * @return array
     */
    public static function getSettings(): array
    {
        return self::$settings;
    }

    public function parse(string|array $velvetCode): string
    {
        $Elements = $this->elementsFrom($velvetCode);

        $Html = [];
        foreach ($Elements as $element) $Html[] = HtmlConverter::convert($element);
        $Html = implode("\n", $Html);

        if (str_ends_with($Html, "\n")) $Html = substr($Html, 0, -1);
        return $Html;
    }

    private function elementsFrom(string|array $lines, $indent = 0): array
    {
        $Elements = [];
        if (is_string($lines)) $lines = explode("\n", $lines);
        $lines = $this->removeComments($lines);
        while (isset($lines[0])) {
            $line = $lines[0];
            array_shift($lines);
            if ($this->notEmpty($line)) {
                try {
                    $parts = RegexDecoder::decode(ltrim($line));
                    $parts = Tools::echoContent($parts);
                    $Element = new HtmlElement($parts);
                    $Element->line = $line;
                    $Element->indent = $this->getIndent($line);
                    while (isset($lines[0]) &&
                        (
                            $Element->indent < $this->getIndent($lines[0])
                            || empty(trim($lines[0]))
                        )) {
                        $Element->lines[] = substr($lines[0], self::$settings['indent_size']);
                        array_shift($lines);
                    }
                    $Element->indent += $indent + 1;
                    if (isset($Element->attributes['filter'])) $Element = $this->filters($Element);
                    else $Element->block = $this->elementsFrom($Element->lines, $Element->indent);
                    $Elements[] = $Element;
                } catch
                (Exception $e) {
                    echo "<strong>VELVET DECODER ERROR</strong>: " . $e->getMessage() . "\n\n";
                    die();
                }
            }
        }
        return $Elements;
    }

    private function removeComments(array $lines): array
    {
        $newLines = [];
        foreach ($lines as $line) {
            if (str_contains($line, "//")) $line = rtrim(substr($line, 0, strpos($line, "//")));
            $newLines[] = $line;
        }
        return $newLines;
    }

    private function notEmpty(string $line): bool
    {
        return !empty(ltrim($line));
    }

    public static function getIndent(string $line): int
    {
        return floor((strlen($line) - strlen(ltrim($line))) / self::$settings['indent_size']);
    }

    /**
     * @throws Exception
     */
    private function filters(HtmlElement $Element): HtmlElement
    {
        foreach ($Element->attributes['filter'] as $filter_value) {
            foreach (explode(" ", $filter_value) as $filter) {
                if (isset(Velvet::$filters[strtolower($filter)])) {
                    $Filter = new Velvet::$filters[strtolower($filter)]();
                    if ($Filter instanceof VelvetFilter) {
                        $Element = $Filter->beforeFiltersElement($Element);
                        $Element = $Filter->applyFilter($Element);
                        if ($Filter->convertElements) $Element->block =
                            $this->elementsFrom($Element->lines, $Element->indent);
                        $Element = $Filter->applyFilterElement($Element);
                    } else throw new Exception("Filter \"$filter\" need to extends \"VelvetFilter\" type");
                } else throw new Exception("Filter \"$filter\" does not exist.");
            }
        }
        unset($Element->attributes['filter']);
        return $Element;
    }

    public function newShortAttribute(string $shortcutSymbol, string $attributeName): void
    {
        RegexDecoder::$prefixes[$shortcutSymbol] = $attributeName;
    }

    public function newFilter(string $filterName, VelvetFilter $class): void
    {
        Velvet::$filters[strtolower($filterName)] = $class;
    }
}