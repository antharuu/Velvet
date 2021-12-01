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
        "used_extensions" => ["vlvt", "velvet"]
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

    public function parse(string $velvetCode): string
    {
        $Elements = $this->elementsFrom($velvetCode);

        $Html = [];
        foreach ($Elements as $element) $Html[] = HtmlConverter::convert($element);

        return implode("\n", $Html);
    }

    private function elementsFrom(string $velvetCode): array
    {
        $Elements = [];
        $lines = $this->removeComments(explode("\n", $velvetCode));
        foreach ($lines as $line) {
            if ($this->notEmpty($line)) {
                try {
                    $parts = RegexDecoder::decode($line);
                    $Element = new HtmlElement($parts);
                    $Elements[] = $Element;
                } catch (Exception $e) {
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

    public function newShortAttribute(string $shortcutSymbol, string $attributeName): void
    {
        RegexDecoder::$prefixes[$shortcutSymbol] = $attributeName;
    }
}