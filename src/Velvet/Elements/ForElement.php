<?php

namespace Antharuu\Velvet\Elements;

use Antharuu\Velvet;
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

            $value_name = null;
            $index_name = null;

            $x = explode("=>", $condition);
            if (count($x) === 1) $value_name = substr($x[0], 1);
            elseif (count($x) === 2) {
                $index_name = substr(trim($x[0]), 1);
                $value_name = substr(trim($x[1]), 1);
            }

            $array = eval("return $array;");

            return $this->for_array($array, $index_name, $value_name);
        } else {
            $r = eval("return $condition;");

            if (is_int($r)) return $this->for_int($r);
            elseif (is_bool($r)) return $this->for_bool($condition);
            elseif (is_array($r)) return $this->for_array($r);
        }

        return "[[FOR LOOP NOT IMPLEMENTED, YET ?]]";
    }

    private function for_array(array $array, string $index_name = null, string $value_name = null): string
    {
        $index_name = $index_name ?? "index";
        $value_name = $value_name ?? "value";

        foreach (Variables::getGlobals() as $var => $__value) $$var = $__value;

        ob_start();
        foreach ($array as $index => $v) {
            Variables::setGlobals([
                    "$index_name" => $index,
                    "$value_name" => $v
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

    private function for_int(int $int): string
    {
        foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;

        ob_start();
        for ($index = 1; $index <= $int; $index++) {
            Variables::setGlobals(["index" => $index]);
            echo $this->getContent();
        }
        return ob_get_clean();
    }

    private function for_bool(string $cond): string
    {
        foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;

        ob_start();
        $security = 0;
        $index = 1;
        while ($security < Velvet\Config::$InfiniteLoopSecurity && eval("return $cond;")) {
            Variables::setGlobals(["index" => $index]);
            foreach (Variables::getGlobals() as $var => $____value) $$var = $____value;

            echo $this->getContent();

            $security++;
            $index++;
        }

        return ob_get_clean();
    }
}