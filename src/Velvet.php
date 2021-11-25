<?php

namespace Antharuu;

use Antharuu\Velvet\Config;
use Antharuu\Velvet\Parser;
use Antharuu\Velvet\Tools;
use Antharuu\Velvet\Variables;

class Velvet extends Velvet\Config
{
    private Parser|null $Parser = null;

    public function __construct($variables = [])
    {
        Variables::setGlobals($variables);
        $this->Parser = new Parser($this);
    }

    public function parseFile(string $fileName, array $variables = []): string|null
    {
        if ($variables !== []) Variables::setGlobals($variables);
        $content = self::getFile($fileName);

        return $content !== null ? $this->parse($content) : null;
    }

    public static function getFile(string $fileName, string $filePath = null): string|null
    {
        $currentPath = $filePath ?? self::$viewPath;
        $currentFile = $currentPath . DIRECTORY_SEPARATOR . self::addExt($fileName);

        foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;
        $currentFile = Tools::echo($currentFile);

        if (file_exists($currentFile)) {
            return file_get_contents($currentFile);
        }


        echo "Sorry we can't find any \"<code>" . self::addExt($fileName) . "</code>\" files in the \"<code>" . $currentPath . "</code>\" folder.";
        die();
    }

    public static function addExt(string $fileName): string
    {
        if (substr($fileName, -5) !== "." . self::$extFile) $fileName .= "." . self::$extFile;

        return $fileName;
    }

    public function parse(string $string): string
    {
        return $this->Parser->transform($string);
    }

    public function customTagsRegister(array $tagsClasses)
    {
        foreach ($tagsClasses as $tag) Config::$customTags[] = $tag;
    }

    public function filterRegister(array $filtersClasses)
    {
        foreach ($filtersClasses as $filter) Config::$filters[] = $filter;
    }
}