<?php

use JetBrains\PhpStorm\NoReturn;

#[NoReturn] function dd(...$data)
{
    echo "<link rel='stylesheet' href='css/prism.css'>";
    foreach ($data as $d):
        vd($d);
    endforeach;
    echo "<script src='js/prism.js'></script>";
    die();
}

function vd(...$data)
{
    foreach ($data as $d):
        echo "<pre class='language-php'><code>";
        var_dump($d);
        echo "</code></pre>";
    endforeach;
}