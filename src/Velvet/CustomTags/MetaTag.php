<?php

namespace Antharuu\Velvet\CustomTags;

class MetaTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "meta";
    }

    public function call()
    {
        $i = $this->element->subtag;
        if ($i == "charset") $this->charset();
        elseif ($i == "viewport") $this->viewport();
    }

    private function charset()
    {
        $this->setAttribute("charset", $this->nextArg(), "UTF-8");
    }

    private function viewport()
    {
        $this->setAttribute("name", "charset");
        $this->setAttribute("content", $this->nextArg(), "width=device-width, initial-scale=1.0");
    }
}