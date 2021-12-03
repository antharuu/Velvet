<?php

namespace Antharuu\Velvet;

class Variable
{
    public static array $variables = [];

    public static function get(string $varName, int $scope = 0): mixed
    {
        return self::getAll($scope)[$varName] ?? null;
    }

    public static function getAll(int $scope = 0): array
    {
        $returnedVariables = [];
        foreach (self::$variables as $sc => $vars) {
            if ($sc >= $scope) {
                foreach ($vars as $name => $value) $returnedVariables[$name] = $value;
            }
        }
        return $returnedVariables;
    }

    public static function addMultiple(array $variables): void
    {
        foreach ($variables as $varName => $varValue) self::add($varName, $varValue);
    }

    public static function add(string $variableName, mixed $value, int $scope = 0): void
    {
        if (!isset(self::$variables[$scope])) self::$variables[$scope] = [];
        self::$variables[$scope][$variableName] = $value;
    }
}