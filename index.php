<?php

use Antharuu\Velvet;
use Antharuu\Velvet\Variable;

require_once "vendor/autoload.php";

$V = new Velvet();
Variable::add("myH1", ["text-primary", "bg-dark"]);

$html = $V->parse(
    'h1(class="myH1") Hello world'
);

dd($html);