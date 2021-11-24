<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$start_time = microtime(TRUE);

$current_page = $_GET['page'] ?? "index";

$V = new Velvet();

$html = $V->parseFile($current_page, ["test" => "layout"]);
echo $html;

dump($html);

$end_time = microtime(TRUE);
$time_taken = ($end_time - $start_time) * 1000;
$time_taken = round($time_taken);

echo 'Page generated in ' . $time_taken . 'ms.';