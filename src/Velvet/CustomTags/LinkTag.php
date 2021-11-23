<?php

namespace Antharuu\Velvet\CustomTags;

class LinkTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "a";
    }

    public function call()
    {
        $this->setAttribute("href", $this->nextArg(), "#");

        if (str_starts_with($this->nextArgValue(), "_")) {
            $this->setAttribute("target", $this->nextArg());
        }
    }
}