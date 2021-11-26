<?php

use Antharuu\Velvet\Elements\ElementInterface;
use Antharuu\Velvet\Elements\HtmlElement;

class PrismElement extends HtmlElement implements ElementInterface
{

    public function getPatern(): string|bool
    {
        $block = [];
        foreach ($this->block as $blkLine) $block[] = substr($blkLine, 2);

        return "<pre class=language-" . ($this->subtag ?? "") . "><code>" . implode("\n", $block) . "</code></pre>";
    }
}