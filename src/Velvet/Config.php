<?php

namespace Antharuu\Velvet;

use Antharuu\Velvet\CustomTags\{DoctypeTag, HeaderLinkTag, ImageTag, LinkTag, MetaTag, ScriptTag};

class Config
{
    public static bool $beautify = true;

    public static int $indentSize = 4;

    public static string $viewPath = "views";
    public static string $templatePath = "views/templates";

    public static string $extFile = "vlvt";

    public static array $customTags = [
        DoctypeTag::class,
        MetaTag::class,
        HeaderLinkTag::class,
        ScriptTag::class,
        LinkTag::class,
        ImageTag::class,
    ];
    public static int $infiniteLoopSecurity = 150;
}