<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$V = new Velvet(["name" => "Anthony"]);

$html = $V->parse(
    'h1= Hello $name {{ \'(my name is in $name)\' }}
h2 Here is my $a = {{md5($a * 56484654) . " - \"\'???\'\""}}'
);

dd($html);