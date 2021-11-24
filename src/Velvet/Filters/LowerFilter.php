<?php

namespace Antharuu\Velvet\Filters;

class LowerFilter extends Filter implements FiltersInterface
{
    public function register(): string
    {
        return "lower";
    }

    public function filterContent(string $content): string
    {
        return strtolower($content);
    }

    public function filterEachBlockLines(string $line): string
    {
        return $line;
    }
}