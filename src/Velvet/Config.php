<?php

namespace Antharuu\Velvet;

use Antharuu\Velvet\CustomTags\ImageTag;
use Antharuu\Velvet\CustomTags\LinkTag;

class Config
{
    // True = Beautify file
    public static bool $Beautify = true;

    // Indentation size
    public static int $indent_size = 4;

    // Detected extension
    public static string $Default_path = "Views";

    // Default views path
    public static string $Ext = "vlvt";

    public static array $CustomTags = [
        LinkTag::class,
        ImageTag::class
    ];
    public static int $InfiniteLoopSecurity = 150;
}