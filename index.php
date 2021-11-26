<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";
require_once "dist/MarkdownFilter.php";
require_once "dist/PrismElement.php";
require_once "dist/PrismTag.php";
require_once "dist/AlertTag.php";

$current_page = $_GET['page'] ?? "index";

$V = new Velvet([
    "current_page" => $current_page,
    "links" => [
        "index" => "Get started",
        "test" => "Try Velvet online !",
        "bases" => "Base syntax",
        "attributes" => "Attributes",
        "variables" => "Variables",
        "conditions" => "Conditions",
        "loops" => "Loops",
        "code" => "PHP Code",
        "layouts" => "Layouts",
        "includes" => "Includes",
    ]
]);

Velvet\Config::$beautify = false;
$V->customTagsRegister([PrismTag::class, AlertTag::class]);
$V->elementRegister(["prism" => PrismElement::class]);
$V->filterRegister([MarkdownFilter::class]);

$html = $V->parseFile($current_page);
echo $html;
