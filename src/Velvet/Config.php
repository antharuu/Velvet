<?php

namespace Antharuu\Velvet;

use Antharuu\Velvet\CustomTags\DoctypeTag;
use Antharuu\Velvet\CustomTags\HeaderLinkTag;
use Antharuu\Velvet\CustomTags\ImageTag;
use Antharuu\Velvet\CustomTags\LinkTag;
use Antharuu\Velvet\CustomTags\MetaTag;
use Antharuu\Velvet\CustomTags\ScriptTag;

class Config
{
    public static bool $Beautify = true;

    public static int $indent_size = 4;

    public static string $Path_views = "views";
    public static string $Path_templates = "views/templates";

    public static string $Ext = "vlvt";

    public static array $CustomTags = [
        DoctypeTag::class,
        MetaTag::class,
        HeaderLinkTag::class,
        ScriptTag::class,
        LinkTag::class,
        ImageTag::class,
    ];
    public static int $InfiniteLoopSecurity = 150;
}