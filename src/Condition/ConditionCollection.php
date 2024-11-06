<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Condition;

class ConditionCollection
{
    /** @var array<ConditionCollectionItem> */
    private array $conditions = [];

    /** @return array<string, array<ConditionCollectionItem>> */
    public function __serialize(): array
    {
        return [
            'conditions' => $this->conditions,
        ];
    }

    /** @param array<string, array<ConditionCollectionItem>> $data */
    public function __unserialize(array $data): void
    {
        $this->conditions = $data['conditions'];
    }

    public function addCondition(string $resultKey, AbstractCondition $condition): void
    {
        $id = md5(serialize($condition) . $resultKey);
        $this->conditions[$id] = new ConditionCollectionItem($resultKey, $condition);
    }

    public function removeCondition(string $id): void
    {
        unset($this->conditions[$id]);
    }

    /** @return array<ConditionCollectionItem> */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /** @param array<ConditionCollectionItem> $conditions */
    public function setConditions(array $conditions): void
    {
        $this->conditions = $conditions;
    }
}
