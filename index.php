<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

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

$html = $V->parseFile($current_page);
echo $html;
