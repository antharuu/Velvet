<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$V = new Velvet();

$html = $V->parse(
    'h1 Bonjour le monde !'
);

dd($html);