<?php

namespace Antharuu;

use Antharuu\Velvet\Parser;
use JetBrains\PhpStorm\Pure;

class Velvet extends Velvet\Config
{
    private Parser|null $Parser = null;

    #[Pure] public function __construct()
    {
        $this->Parser = new Parser($this);
    }

    public function parse_file(string $fileName, string $filePath = null): string|null
    {
        $currentPath = $filePath ?? self::$Default_path;
        $currentFile = $currentPath . DIRECTORY_SEPARATOR . $this->addExt($fileName);

        if (file_exists($currentFile)) {
            return $this->parse(file_get_contents($currentFile));
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