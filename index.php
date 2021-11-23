<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$current_page = $_GET['page'] ?? "index";

$V = new Velvet();

$V->customTagsRegister([
    Velvet\CustomTags\ImageTag::class
]);

$html = $V->parseFile($current_page);
echo $html;

dump($html);