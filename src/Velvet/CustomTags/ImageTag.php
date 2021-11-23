<?php

namespace Antharuu\Velvet\CustomTags;

class ImageTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "img";
    }

    public function call()
    {
        $this->setAttribute("src", $this->nextArg());
        $this->setAttribute("alt", $this->nextArgs());
    }
}