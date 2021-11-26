<?php

use Antharuu\Velvet\Filters\{Filter, FiltersInterface};
use Antharuu\Velvet\Tools;
use Michelf\Markdown;

class MarkdownFilter extends Filter implements FiltersInterface
{

    public function register(): string
    {
        return "markdown";
    }

    public function filterContent(string $content): string
    {
        return $this->getMarkdown($content);
    }

    private function getMarkdown($lines): string
    {
        if (strlen(trim($lines)) <= 0) return "";
        $M = new Markdown();
        $lines = Tools::echo($lines);
        $newLines = [];
        foreach (explode("\n\n", $lines) as $l) {
            $newLines[] = "| " . $M->transform(str_replace("\n", "", $l));
        }
        return implode("", $newLines);
    }

    public function filterEachBlockLines(string $line): string
    {
        return $this->getMarkdown($line);
    }
}