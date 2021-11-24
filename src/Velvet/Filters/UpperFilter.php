<?php

namespace Antharuu\Velvet\Filters;

class UpperFilter extends Filter implements FiltersInterface
{
    public function register(): string
    {
        return "upper";
    }

    public function filterContent(string $content): string
    {
        return strtoupper($content);
    }

    public function filterEachBlockLines(string $line): string
    {
        return $line;
    }
}