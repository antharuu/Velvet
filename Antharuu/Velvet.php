<?php

namespace Antharuu;

use Antharuu\Velvet\{Elements\HtmlElement, HtmlConverter, RegexDecoder, Tools, Variable};
use Antharuu\Velvet\{Filters\LowerFilter, Filters\MarkdownFilter, Filters\UpperFilter, Filters\VelvetFilter};
use Exception;
use function array_shift;
use function max;
use function substr;
use function trim;

/**
 * The template engine
 */
class Velvet
{
    /**
     * Used settings
     *
     * @var array
     */
    protected static array $settings = [
        "default_path" => "views",
        "layout_path" => "layout",
        "used_extensions" => ["vlvt", "velvet"],
        "indent_size" => 4,
        "minimize" => false,
        "separator" => "#!:%~|~%:!#"
    ];

    /**
     * Registered filters
     *
     * @var VelvetFilter[]
     */
    private static array $filters = [
        "upper" => UpperFilter::class,
        "lower" => LowerFilter::class,
        "md" => MarkdownFilter::class,
        "markdown" => MarkdownFilter::class,
    ];

    /**
     * Initialize the template engine
     *
     * @param array $variables Variables for all the template (Example: ["name" => "Paul"])
     * @param array $settings Settings (Example: ["indent_size" => 4])
     */
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

    /**
     * @param string $string
     * @return mixed
     */
    public static function getSetting(string $string): mixed
    {
        return self::$settings[$string] ?? null;
    }

    /**
     * @param string|array $velvetCode
     * @return string
     */
    public function parse(string|array $velvetCode): string
    {
        $Elements = $this->elementsFrom($velvetCode);

        $Html = [];
        foreach ($Elements as $element) $Html[] = HtmlConverter::convert($element);
        $Html = implode("\n", $Html);

        if (str_ends_with($Html, "\n")) $Html = substr($Html, 0, -1);
        return $Html;
    }

    /**
     * Transform velvet lines to array of HtmlElement
     *
     * @param string|string[] $lines String or array of string of velvet code
     * @param int $indent Current indentation / scope
     * @return HtmlElement[]
     */
    private function elementsFrom(string|array $lines, int $indent = 0): array
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
                    if ($parts['code'] ?? false) continue;
                    $parts = Tools::echoContent($parts);
                    $Element = new HtmlElement($parts);
                    $Element->line = $line;
                    $Element->indent = $this->getIndent($line) - $indent;
                    $Element->indent = max($Element->indent, 0);
                    while (isset($lines[0]) &&
                        (
                            $Element->indent < $this->getIndent($lines[0])
                            || empty(trim($lines[0]))
                        )) {
                        $Element->lines[] = substr($lines[0], Velvet::$settings['indent_size']);
                        array_shift($lines);
                    }
                    $Element->indent = $indent + 1;
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

    /**
     * @param array $lines
     * @return array
     */
    private function removeComments(array $lines): array
    {
        $newLines = [];
        foreach ($lines as $line) {
            if (str_contains($line, "//")) $line = rtrim(substr($line, 0, strpos($line, "//")));
            $newLines[] = $line;
        }
        return $newLines;
    }

    /**
     * @param string $line
     * @return bool
     */
    private function notEmpty(string $line): bool
    {
        return !empty(ltrim($line));
    }

    /**
     * @param string $line
     * @return int
     */
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

    /**
     * @param string $shortcutSymbol
     * @param string $attributeName
     * @return void
     */
    public function newShortAttribute(string $shortcutSymbol, string $attributeName): void
    {
        RegexDecoder::$prefixes[$shortcutSymbol] = $attributeName;
    }

    /**
     * @param string $filterName
     * @param VelvetFilter $class
     * @return void
     */
    public function newFilter(string $filterName, VelvetFilter $class): void
    {
        Velvet::$filters[strtolower($filterName)] = $class;
    }
}