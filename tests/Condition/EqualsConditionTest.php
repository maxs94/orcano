<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Condition;

use App\Condition\EqualsCondition;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \App\Condition\EqualsCondition
 */
class EqualsConditionTest extends TestCase
{
    /** @dataProvider equalsDataProvider */
    public function testEqualsCondition(bool $expected, mixed $value): void
    {
        $condition = new EqualsCondition($value);
        $result = $condition->checkIfOk($value);
        $this->assertSame($expected, $result);
    }

    /** @return array<string, array{bool, mixed}> */
    public function equalsDataProvider(): array
    {
        return [
            'int' => [true, 1],
            'float' => [true, 1.0],
            'string' => [true, '1'],
            'bool' => [true, true],
        ];
    }
}
