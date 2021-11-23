<?php

namespace Antharuu\Velvet\Elements;

use Antharuu\Velvet;
use Antharuu\Velvet\Config;
use Antharuu\Velvet\Variables;

class ForElement extends HtmlElement implements ElementInterface
{

    public function getPatern(): string|bool
    {
        foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;

        $condition = $this->content;

        $re = '/^(?\'array\'.+)( +)as( +)(?\'condition\'.*)$/m';
        preg_match_all($re, $condition, $matches, PREG_SET_ORDER, 0);

        if (count($matches) > 0) {
            $array = $matches[0]['array'];
            $condition = $matches[0]['condition'];

            $valueName = null;
            $indexName = null;

            $x = explode("=>", $condition);
            if (count($x) === 1) $valueName = substr($x[0], 1);
            elseif (count($x) === 2) {
                $indexName = substr(trim($x[0]), 1);
                $valueName = substr(trim($x[1]), 1);
            }

            $array = eval("return $array;");

            return $this->forArray($array, $indexName, $valueName);
        } else {
            $r = eval("return $condition;");

            if (is_int($r)) return $this->forInt($r);
            elseif (is_bool($r)) return $this->forBool($condition);
            elseif (is_array($r)) return $this->forArray($r);
        }

        return "[[FOR LOOP NOT IMPLEMENTED, YET ?]]";
    }

    private function forArray(array $array, string $indexName = null, string $valueName = null): string
    {
        $indexName = $indexName ?? "index";
        $valueName = $valueName ?? "value";

        foreach (Variables::getGlobals() as $var => $__value) $$var = $__value;

        ob_start();
        foreach ($array as $index => $v) {
            Variables::setGlobals([
                    "$indexName" => $index,
                    "$valueName" => $v
                ]
            );
            echo $this->getContent();
        }
        return ob_get_clean();
    }

    private function getContent(): string
    {
        foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;

        $this->content = "";
        $V = new Velvet();
        return $V->parse(implode("\n", $this->block));
    }

    private function forInt(int $int): string
    {
        foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;

        ob_start();
        for ($index = 1; $index <= $int; $index++) {
            Variables::setGlobals(["index" => $index]);
            echo $this->getContent();
        }
        return ob_get_clean();
    }

    private function forBool(string $cond): string
    {
        foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;

        ob_start();
        $security = 0;
        $index = 1;
        while ($security < Config::$infiniteLoopSecurity && eval("return $cond;")) {
            Variables::setGlobals(["index" => $index]);
            foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;

            echo $this->getContent();

            $security++;
            $index++;
        }

        return ob_get_clean();
    }
}