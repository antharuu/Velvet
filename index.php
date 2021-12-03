<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$V = new Velvet();

$html = $V->parse(
    'h1#title!upper Hello world !
    small.test ok ?
    |  nice'
);

dd($html);