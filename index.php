<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$current_page = $_GET['page'] ?? "index";

$V = new Velvet();
$html = $V->parse_file($current_page);
echo $html;

dump($html);