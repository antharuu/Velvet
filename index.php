<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$V = new Velvet();

$html = $V->parse(
    '#box
    #sub-box
        #sub-sub-box-1
            h1#title Hello World !
        #sub-sub-box-2
        
            #sub-sub-sub-box
            
                h2#sub-title Como esta ?
                    span
            #osef
        #sub-sub-box-3
            h3#sub-sub-title Oui.'
);

dd($html);