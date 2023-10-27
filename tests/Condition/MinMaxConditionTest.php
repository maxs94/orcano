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
 * @coversNothing
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

        $result = $condition->checkIfOk($values['value']);

        $this->assertSame($expected, $result);
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
}
