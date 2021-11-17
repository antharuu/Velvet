<?php

namespace Antharuu\Velvet;

use Antharuu\Velvet\Elements\HtmlElement;

class Parser
{
    private static string $Regex =
        "#^(?'tag'[a-zA-Z\_\-]{1}[a-zA-Z0-9\_\-]*)
(?'ids'\#[a-zA-Z0-9\-\#\_]+)?
(?'classes'\.[a-zA-Z0-9\-\.\_]+)?
 ?(?'content'.*)$#";

    private static array $Lines = [];
    private static array $HtmlLines = [];

    public static function transform(string $vlvtCode): string
    {
        self::$Lines = explode("\n", $vlvtCode);
        self::cleanupLines();
        self::htmlConverter(self::$Lines);
        return implode("\n", self::$HtmlLines);
    }

    private static function cleanupLines()
    {
        $newLines = [];

        foreach (self::$Lines as $line):
            if (!empty(trim($line))):
                $newLines[] = $line;
            endif;
        endforeach;

        self::$Lines = $newLines;
    }

    private static function htmlConverter(array $Lines)
    {
        foreach ($Lines as $line):
            $res = preg_match_all(str_replace("\n", "", self::$Regex), $line, $matches, PREG_SET_ORDER, 0);

            $Element = new HtmlElement();
            $Element->tag = trim($matches[0]['tag']);
            $Element->content = $matches[0]['content'];
            $Element->setAttribute("id", explode("#", $matches[0]['ids']));
            $Element->setAttribute("class", explode(".", $matches[0]['classes']));

            self::$HtmlLines[] = $Element->getHtml();

        endforeach;
    }
}