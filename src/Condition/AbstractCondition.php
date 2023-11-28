<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Condition;

abstract class AbstractCondition implements ConditionInterface
{
    public function __toString(): string
    {
        $reflectionClass = new \ReflectionClass(static::class);

        return $reflectionClass->getShortName();
    }

    public function getConditionClassName(): string
    {
        return static::class;
    }

    /** @return array<string> */
    public function getParameters(): array
    {
        $reflection = new \ReflectionClass(static::class);
        $parameters = $reflection->getConstructor()->getParameters();

        $result = [];
        foreach ($parameters as $parameter) {
            $result[] = $parameter->getName();
        }

        return $result;
    }

    public function get(string $parameterName): mixed
    {
        $getter = 'get' . ucfirst($parameterName);
        if (!method_exists($this, $getter)) {
            throw new \Exception(sprintf('Could not find getter %s in %s', $getter, static::class));
        }

        return $this->{$getter}();
    }
}
