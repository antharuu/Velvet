<?php

namespace Antharuu;

use Antharuu\Velvet\Parser;
use Antharuu\Velvet\Variables;
use JetBrains\PhpStorm\Pure;

class Velvet extends Velvet\Config
{
    private Parser|null $Parser = null;

    #[Pure] public function __construct($variables = [])
    {
        Variables::setGlobals($variables);
        $this->Parser = new Parser($this);
    }

    public function parse_file(string $fileName, string $filePath = null): string|null
    {
        $content = self::get_file($fileName);

        return $content !== null ? $this->parse($content) : null;
    }

    public static function get_file(string $fileName, string $filePath = null): string|null
    {
        $currentPath = $filePath ?? self::$Path_views;
        $currentFile = $currentPath . DIRECTORY_SEPARATOR . self::addExt($fileName);

        if (file_exists($currentFile)) {
            return file_get_contents($currentFile);
        } else {
            echo "Sorry we can't find any \"<code>" . self::addExt($fileName) . "</code>\" files 
in the \"<code>" . $currentPath . "</code>\" folder.";
        }

        return null;
    }

    public static function addExt(string $fileName): string
    {
        if (substr($fileName, -5) !== "." . self::$Ext) $fileName .= "." . self::$Ext;

        return $fileName;
    }

    public function parse(string $string): string
    {
        return $this->Parser->transform($string);
    }


}