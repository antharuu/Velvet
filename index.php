<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$V = new Velvet();
ob_start();
echo "
h1#oui.non#bonjour(test='pas ok')!markdown(test=ok disabled)  Hello world
";
$html = $V->parse(ob_get_clean());

dd($html);