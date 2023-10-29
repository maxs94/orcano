<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Condition;

use App\Condition\ConditionCollection;
use App\Condition\EqualsCondition;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Condition\ConditionCollection
 *
 * @internal
 */
class ConditionCollectionTest extends TestCase
{
    public function testSerialization(): void
    {
        $collection = new ConditionCollection();
        $serialized = serialize($collection);
        $this->assertEquals($collection, unserialize($serialized));
    }

    public function testCollection(): void
    {
        $collection = new ConditionCollection();
        $condition1 = new EqualsCondition(1);
        $collection->addCondition('result', $condition1);

        $this->assertEquals($condition1, $collection->getConditions()['result']);
    }
}
