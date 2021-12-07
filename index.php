<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$V = new Velvet();

//$html = $V->parse(
//    '? $a = true
//if $a
//    h1 Hello A
//else
//    h1 Hello B'
//);
$html = $V->parse(
    'h1 Bonjour le monde
    small comment ça va ?'
);

dd($html);