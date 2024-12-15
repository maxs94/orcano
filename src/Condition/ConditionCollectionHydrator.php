<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Condition;

use Psr\Log\LoggerInterface;

class ConditionCollectionHydrator
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    /** @param array<string, array<string, mixed>> $conditions */
    public function hydrateFromFormPost(array $conditions): ConditionCollection
    {
        $result = new ConditionCollection();

        foreach ($conditions as $conditionData) {
            $conditionClassName = $conditionData['name'] ?? null;

            if (empty($conditionClassName)) {
                $this->logger->warning(sprintf('Could not find condition class name in %s', json_encode($conditionData, JSON_THROW_ON_ERROR)));
                continue;
            }

            $reflectionClass = new \ReflectionClass($conditionClassName);

            $parameters = $reflectionClass->getConstructor()->getParameters();

            $parameterValues = [];
            foreach ($parameters as $parameter) {
                $parameterValues[] = $conditionData[$parameter->getName()] ?? null;
            }

            $condition = $reflectionClass->newInstance(...$parameterValues);

            $result->addCondition($conditionData['key'], $condition);
        }

        return $result;
    }
}
