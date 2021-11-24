<?php

namespace Antharuu\Velvet\Filters;

interface FiltersInterface
{
    public function register(): string;

    public function filterContent(string $content): string;

    public function filterEachBlockLines(string $line): string;
}