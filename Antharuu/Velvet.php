<?php

namespace Antharuu;

use Antharuu\Velvet\RegexDecoder;

class Velvet
{
    protected static array $settings = [
        "default_path" => "views",
        "layout_path" => "layout",
        "used_extensions" => ["vlvt", "velvet"]
    ];

    public function __construct(
        array $settings = []
    )
    {
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
        $Elements = $this->elementFrom($velvetCode);

        return "<h1>Hello world</h1>";
    }

    private function elementFrom(string $velvetCode)
    {
        foreach (explode("\n", $velvetCode) as $line) {
            if ($this->notEmpty($line)) {
                RegexDecoder::decode($line);
            }
        }
    }

    private function notEmpty(string $line): bool
    {
        return !empty(ltrim($line));
    }
}