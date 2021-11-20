<?php

namespace Antharuu\Velvet\CustomTags;

use Antharuu\Velvet\Elements\HtmlElement;

interface CustomTagInterface
{
    public function register(): string; // return the tag name

    public function call(array $args, HtmlElement $BlockElement): HtmlElement; // return the HtmlElement
}