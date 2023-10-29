<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Condition;

use App\Condition\Criteria;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Condition\Criteria
 *
 * @internal
 */
class CriteriaTest extends TestCase
{
    public function testValidOperator(): void
    {
        foreach (Criteria::VALID_OPERATORS as $operator) {
            $criteria = new Criteria('result', $operator, 1, [1, 2, 3]);
            $this->assertEquals($operator, $criteria->getOperator());
            $this->assertEquals('result', $criteria->getField());
            $this->assertEquals(1, $criteria->getValue());
            $this->assertEquals([1, 2, 3], $criteria->getValues());
        }
    }

    public function testInvalidOperator(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Criteria('result', 'invalid', 1, [1, 2, 3]);
    }
}
