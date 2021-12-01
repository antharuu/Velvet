<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$V = new Velvet();
$V->newShortAttribute("'", "oui");
$html = $V->parse('h2\'sous-titre Mais oui c\'est clair');

dd($html);