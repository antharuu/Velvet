<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$V = new Velvet();

$html = $V->parse(
    'h1(class=$myH1) Hello world'
);

dd($html);