<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Condition;

use App\Condition\MinMaxCondition;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \App\Condition\MinMaxCondition
 */
class MinMaxConditionTest extends TestCase
{
    /**
     * @dataProvider minMaxConditionDataProvider
     *
     * @param array<string, mixed> $values
     * */
    public function testMinMaxCondition(bool $expected, array $values): void
    {
        $condition = new MinMaxCondition(
            $values['min'],
            $values['max']
        );

        $condition->setOperator(MinMaxCondition::DEFAULT_OPERATOR);

        $result = $condition->checkIfOk($values['value']);

        $this->assertSame($expected, $result);
        $this->assertSame($condition->getOperator(), MinMaxCondition::DEFAULT_OPERATOR);
    }

    /** @return array<string, array<string, mixed>> */
    public function minMaxConditionDataProvider(): array
    {
        return [
            'max only' => [
                'expected' => true,
                'values' => [
                    'min' => null,
                    'max' => 10,
                    'value' => 5,
                ],
            ],
            'min only' => [
                'expected' => true,
                'values' => [
                    'min' => 10,
                    'max' => null,
                    'value' => 15,
                ],
            ],
            'value below min' => [
                'expected' => false,
                'values' => [
                    'min' => 10,
                    'max' => null,
                    'value' => 5,
                ],
            ],
            'min and max' => [
                'expected' => true,
                'values' => [
                    'min' => 10,
                    'max' => 20,
                    'value' => 15,
                ],
            ],
        ];
    }

    public function testSerialization(): void
    {
        $condition = new MinMaxCondition(1, 2);
        $serialized = serialize($condition);
        $this->assertEquals($condition, unserialize($serialized));
    }

    public function testMinMaxWarnCondition(): void
    {
        $condition = new MinMaxCondition(1, 10, 2, 10);
        $result = $condition->checkIfWarn(8);

        $this->assertTrue($result);
    }

    public function testInvalidOperator(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $condition = new MinMaxCondition(1, 10, 2, 10);
        $condition->setOperator('invalid');
    }

    public function testNonNumericValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $condition = new MinMaxCondition(1, 10, 2, 10);
        $condition->checkIfOk('invalid');
    }
}
