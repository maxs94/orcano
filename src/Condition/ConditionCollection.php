<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Condition;

class ConditionCollection
{
    /** @var array<string, AbstractCondition> */
    private array $conditions = [];

    /** @return array<string, array<string, AbstractCondition>> */
    public function __serialize(): array
    {
        return [
            'conditions' => $this->conditions,
        ];
    }

    /** @param array<string, array<string, AbstractCondition>> $data */
    public function __unserialize(array $data): void
    {
        $this->conditions = $data['conditions'];
    }

    public function addCondition(string $resultKey, AbstractCondition $condition): void
    {
        $this->conditions[$resultKey] = $condition;
    }

    /** @return array<string, AbstractCondition> */
    public function getConditions(): array
    {
        return $this->conditions;
    }
}
