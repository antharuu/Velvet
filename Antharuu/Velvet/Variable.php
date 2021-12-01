<?php

namespace Antharuu\Velvet;

class Variable
{
    public static array $variables = [];

    public static function add(string $variableName, mixed $value, int $scope = 0)
    {
        if (!isset(self::$variables[$scope])) self::$variables[$scope] = [];
        self::$variables[$scope][$variableName] = $value;
    }

    public static function get(string $varName, int $scope = 0): mixed
    {
        return self::getAll($scope)[$varName] ?? null;
    }

    private static function getAll(int $scope = 0): array
    {
        $returnedVariables = [];
        foreach (self::$variables as $sc => $vars) {
            if ($sc >= $scope) {
                foreach ($vars as $name => $value) $returnedVariables[$name] = $value;
            }
        }
        return $returnedVariables;
    }
}