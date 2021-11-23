<?php

namespace Antharuu\Velvet\CustomTags;

interface CustomTagInterface
{
    public function register(): string; // return the tag name

    public function call();
}