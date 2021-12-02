<?php

namespace Antharuu\Velvet\Filters;

use Antharuu\Velvet\Interfaces\FilterInterface;

class LowerFilter extends VelvetFilter implements FilterInterface
{
    public bool $linesAsContent = true;

    public function filterContent(string $content): string
    {
        return strtolower($content);
    }

}