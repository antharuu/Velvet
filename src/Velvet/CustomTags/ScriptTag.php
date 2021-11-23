<?php

namespace Antharuu\Velvet\CustomTags;

class ScriptTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "script";
    }

    public function call()
    {
        if ($this->element->subtag === "js") $this->element->subtag = "text/javascript";

        $this->subtagReplace(["js", "javascript"], "text/javascript");

        $this->nextArgAction("defer", function () {
            $this->setAttribute("defer");
        });

        $this->setAttribute("type", $this->element->subtag);
        $this->setAttribute("src", $this->nextArg(), "#");

        $this->element->content = "";
        $this->element->block = [];
    }
}