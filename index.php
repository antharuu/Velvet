<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$V = new Velvet();
$V->newShortAttribute("'", "oui");

$html = $V->parse(
    'h1 Hello
    small world'
);

dd($html);