<?php

use Antharuu\Velvet;

require_once "vendor/autoload.php";

$V = new Velvet();

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
<div id="title">Velvet<span class="version">0.1 Alpha</span></div>
<div id="codes">
    <pre><code class="language-pug"><?= file_get_contents("Tests/test.vlvt") ?></code></pre>
    <pre><code class="language-html"><?= htmlentities($File) ?></code></pre>
</div>
</body>
</html>
