<?php

namespace Antharuu;

use Antharuu\Velvet\Parser;
use JetBrains\PhpStorm\Pure;

class Velvet extends Velvet\Config
{
    public function __construct()
    {
    }

    public function parse_file(string $fileName, string $filePath = null): string|null
    {
        $currentPath = $filePath ?? $this->Default_path;
        $currentFile = $currentPath . DIRECTORY_SEPARATOR . $this->addExt($fileName);

        if (file_exists($currentFile)) {
            return Parser::transform(file_get_contents($currentFile));
        }

        return null;
    }

    private function addExt(string $fileName): string
    {
        if (substr($fileName, -5) !== "." . $this->Ext) $fileName .= "." . $this->Ext;

        return $fileName;
    }


}