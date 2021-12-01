<?php

namespace Antharuu;

use Antharuu\Velvet\Elements\HtmlElement;
use Antharuu\Velvet\HtmlConverter;
use Antharuu\Velvet\RegexDecoder;
use Antharuu\Velvet\Variable;
use Exception;

class Velvet
{
    protected static array $settings = [
        "default_path" => "views",
        "layout_path" => "layout",
        "used_extensions" => ["vlvt", "velvet"],
        "indent_size" => 4
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

        return implode("\n", $Html);
    }

    private function elementsFrom(string|array $lines): array
    {
        $Elements = [];
        if (is_string($lines)) $lines = explode("\n", $lines);
        $lines = $this->removeComments($lines);
        while (isset($lines[0])) {
            $line = $lines[0];
            array_shift($lines);
            if ($this->notEmpty($line)) {
                try {
                    $parts = RegexDecoder::decode($line);
                    $Element = new HtmlElement($parts);
                    $Element->indent = $this->getIndent($line);
                    while (
                        isset($lines[0]) &&
                        $Element->indent < $this->getIndent($lines[0])
                    ) {
                        $Element->block[] = substr($lines[0], self::$settings['indent_size']);
                        array_shift($lines);
                    }
                    $Element->block = $this->elementsFrom($Element->block);
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

    public function newShortAttribute(string $shortcutSymbol, string $attributeName): void
    {
        RegexDecoder::$prefixes[$shortcutSymbol] = $attributeName;
    }
}