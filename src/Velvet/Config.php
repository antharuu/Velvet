<?php

namespace Antharuu\Velvet;

use Antharuu\Velvet\CustomTags\{DoctypeTag, HeaderLinkTag, ImageTag, LinkTag, MetaTag, ScriptTag};
use Antharuu\Velvet\Filters\LowerFilter;
use Antharuu\Velvet\Filters\UpperFilter;

class Config
{
    public static bool $beautify = true;

    public static int $indentSize = 4;

    public static string $viewPath = "views";
    public static string $templatePath = "views/templates";

    public static string $extFile = "vlvt";

    public static int $infiniteLoopSecurity = 150;

    public static array $customTags = [
        DoctypeTag::class,
        MetaTag::class,
        HeaderLinkTag::class,
        ScriptTag::class,
        LinkTag::class,
        ImageTag::class,
    ];

    public static array $filters = [
        UpperFilter::class,
        LowerFilter::class,
    ];

    public static array $selfClose = [
        "!DOCTYPE",
        "area",
        "base",
        "br",
        "col",
        "embed",
        "hr",
        "img",
        "input",
        "link",
        "meta",
        "param",
        "source",
        "track",
        "wbr",
        "command",
        "keygen",
        "menuitem"
    ];
    public static array $elementsPaterns = [
        "extends",
        "block",
        "include",
        "?" => "code",
        "=" => "echo",
        "for",
        "if" => "condition",
        "else" => "condition",
        "elseif" => "condition",
        "else if" => "condition",
    ];
}