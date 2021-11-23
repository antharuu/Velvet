<?php

namespace Antharuu\Velvet\Elements;

use Antharuu\Velvet\Parser;

class BlockElement extends HtmlElement implements ElementInterface
{

    public function getPatern(): string|bool
    {
        Parser::$blocks[trim($this->content)] = $this->block;

        return "";
    }
}