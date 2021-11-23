<?php

namespace Antharuu\Velvet\CustomTags;

class HeaderLinkTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "link";
    }

    public function call()
    {
        if ($this->element->subtag === "css") $this->element->subtag = "stylesheet";

        $this->setAttribute("rel", $this->element->subtag);
        $this->setAttribute("href", $this->nextArg(), "#");
    }

}