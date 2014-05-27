<?php

namespace Ranking\Validation;

use Respect\Validation\Validator as OriginalValidator;

class Validator extends OriginalValidator
{
    private static function createRuleReflection($ruleName)
    {
        $searchInNamespaces = array(
            'Respect\\Validation\\Rules',
            'Ranking\\Validation\\Rules'
        );
        foreach ($searchInNamespaces as $namespace) {
            $fullClassName = sprintf($namespace.'\\%s', ucfirst($ruleName));
            if (false === class_exists($fullClassName)) {
                continue;
            }

            return new \ReflectionClass($fullClassName);
        }
        throw new \UnexpectedValueException(sprintf('Rule "%s" does not exists in namespaces: %s', $ruleName, implode(', ', $searchInNamespaces)));
    }

    public static function buildRule($ruleSpec, $arguments=array())
    {
        if ($ruleSpec instanceof Validatable) {
            return $ruleSpec;
        }

        try {
            $validatorClass = self::createRuleReflection($ruleSpec);
            $validatorInstance = $validatorClass->newInstanceArgs(
                    $arguments
            );

            return $validatorInstance;
        } catch (ReflectionException $e) {
            throw new ComponentException($e->getMessage());
        }
    }
}
