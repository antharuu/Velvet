<?php

namespace Antharuu\Velvet\Filters;

use Antharuu\Velvet\Interfaces\FilterInterface;

class UpperFilter extends VelvetFilter implements FilterInterface
{
    public bool $linesAsContent = true;

    public function filterContent(string $content): string
    {
        return strtoupper($content);
    }

}