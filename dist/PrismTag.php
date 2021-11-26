<?php

use Antharuu\Velvet\CustomTags\CustomTag;
use Antharuu\Velvet\CustomTags\CustomTagInterface;

class PrismTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "prism";
    }

    public function call()
    {
        $newBlock = [];
        foreach ($this->element->block as $blkLine) $newBlock[] = "| " . htmlentities($blkLine);
        $this->element->block = $newBlock;
        $this->element->keepStrict = true;
    }
}