<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Condition;

use App\DataObject\Collection\DataObjectCollection;

class ConditionCollection extends DataObjectCollection
{
    /** @var array<string, ConditionCollectionItem> */
    protected array $objects = [];

    /** @return array<string, array<ConditionCollectionItem>> */
    public function __serialize(): array
    {
        return [
            'conditions' => $this->objects,
        ];
    }

    /** @param array<string, array<ConditionCollectionItem>> $data */
    public function __unserialize(array $data): void
    {
        $this->objects = $data['objects'] ?? [];
    }

    public function addCondition(string $resultKey, AbstractCondition $condition): void
    {
        $id = md5(serialize($condition) . $resultKey);
        $this->objects[$id] = new ConditionCollectionItem($resultKey, $condition);
    }

    public function removeCondition(string $id): void
    {
        unset($this->objects[$id]);
    }

    /** @return array<ConditionCollectionItem> */
    public function getConditions(): array
    {
        return $this->objects;
    }

    /** @param array<ConditionCollectionItem> $objects */
    public function setConditions(array $objects): void
    {
        $this->objects = $objects;
    }
}
