<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$V = new Velvet();

$V::$Default_path = "Tests";
$File = $V->parse_file("test", "Tests");

?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Velvet test page</title>

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/prism.css">
    <script src="js/prism.js" defer></script>
</head>
<body>
<div id="title">Velvet<span class="version">0.11 Alpha</span></div>
<div id="codes">
    <div class="codebox">
        <pre><code class="language-pug"><?= htmlentities(file_get_contents("Tests/test.vlvt")) ?></code></pre>
    </div>
    <div class="codebox">
        <pre><code class="language-html"><?= htmlentities($File) ?></code></pre>
    </div>
</div>
</body>
</html>
