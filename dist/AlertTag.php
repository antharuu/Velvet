<?php

use Antharuu\Velvet\CustomTags\CustomTag;
use Antharuu\Velvet\CustomTags\CustomTagInterface;

class AlertTag extends CustomTag implements CustomTagInterface
{

    public function register(): string
    {
        return "alert";
    }

    public function call()
    {
        $this->element->block = [
            ".alert.alert-primary.d-flex.align-items-center(role='alert')",
            "    .material-icons.me-3 " . $this->element->subtag,
            "    | " . $this->nextArgs()
        ];

        $this->element->content = "";
    }
}