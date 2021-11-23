<?php

namespace Antharuu\Velvet\CustomTags;

use Antharuu\Velvet\Elements\HtmlElement;
use Closure;

abstract class CustomTag implements CustomTagInterface
{
    public string $tag;
    public array $args = [];

    public function __construct(
        public HtmlElement $element
    )
    {
        $this->tag = $this->register();
    }

    public function clear(): HtmlElement
    {
        array_unshift($this->element->block, "| " . implode(" ", $this->args));
        $this->element->content = "";

        return $this->element;
    }

    protected function nextArgAction(string $searchArg, Closure $callback): void
    {
        if (strtolower(trim($searchArg)) === strtolower(trim($this->args[0]))) {
            $this->nextArg();
            $callback();
        }
    }

    protected function nextArg(int $count = 1): string
    {
        if ($count === 0) $count = count($this->args);

        $rArgs = [];

        for ($i = 0; $i < $count; $i++) {
            if (isset($this->args[0])) $rArgs[] = array_shift($this->args);
        }

        return count($rArgs) > 0 ? trim(implode(" ", $rArgs)) : false;
    }

    protected function nextArgs(): string
    {
        return $this->nextArg(0);
    }

    protected function nextArgsValues(): string
    {
        return $this->nextArgValue(0);
    }

    protected function nextArgValue(int $count = 1): string
    {
        if ($count === 0) $count = count($this->args);

        $rArgs = [];

        for ($i = 0; $i < $count; $i++) {
            if (isset($this->args[0])) $rArgs[] = $this->args[0];
        }

        return count($rArgs) > 0 ? trim(implode(" ", $rArgs)) : false;
    }

    protected function subtagReplace(string|array $search, string $replace): bool
    {
        if (!is_array($search)) $search = [$search];
        foreach ($search as $lf) {
            if ($this->element->subtag === $lf) {
                $this->element->subtag = $replace;
                return true;
            }
        }

        return false;
    }

    protected function setAttribute(string $attributeName, string|array $values = "", string $defaultValue = ""): void
    {
        $this->element->setAttribute($attributeName, $values, $defaultValue);
    }
}