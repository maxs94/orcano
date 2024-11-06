<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Condition;

abstract class AbstractCondition implements ConditionInterface, \Stringable
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

    protected function setData(string $key, mixed $value): void 
    {
        if (is_float($value)) {
            $value = (float) $value;
        } else if (is_numeric($value)) {
            $value = (int) $value;
        } else if (is_bool($value)) {
            $value = (bool) $value;
        } else if (is_string($value)) {
            $value = (string) $value;
        }

        $this->{$key} = $value;
    }

}
