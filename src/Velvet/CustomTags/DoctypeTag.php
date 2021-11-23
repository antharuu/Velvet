<?php

namespace Antharuu\Velvet\CustomTags;

class DoctypeTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "doctype";
    }

    public function call()
    {
        $this->element->tag = "!DOCTYPE";
        $this->setAttribute("html");
    }
}