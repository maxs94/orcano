<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Condition;

class MinMaxCondition extends AbstractCondition
{
    public const VALID_OPERATORS = [
        'and',
        'or',
    ];

    public const DEFAULT_OPERATOR = 'and';

    private string $operator = self::DEFAULT_OPERATOR;

    public function __construct(private mixed $okMin = null, private mixed $okMax = null, private mixed $warnMin = null, private mixed $warnMax = null) {}

    /**
     * @return array<string, int|string|null>
     */
    public function __serialize(): array
    {
        return [
            'warnMin' => $this->warnMin,
            'warnMax' => $this->warnMax,
            'okMin' => $this->okMin,
            'okMax' => $this->okMax,
            'operator' => $this->operator,
        ];
    }

    /**
     * @param array<string, int|string|null> $data
     */
    public function __unserialize(array $data): void
    {
        $this->warnMin = $data['warnMin'];
        $this->warnMax = $data['warnMax'];
        $this->okMin = $data['okMin'];
        $this->okMax = $data['okMax'];
        $this->operator = $data['operator'];
    }

    public function checkIfOk(mixed $value): bool
    {
        return $this->check($this->okMin, $this->okMax, $value);
    }

    public function checkIfWarn(mixed $value): bool
    {
        return $this->check($this->warnMin, $this->warnMax, $value);
    }

    public function setOperator(string $operator): void
    {
        if (!in_array($operator, self::VALID_OPERATORS)) {
            throw new \InvalidArgumentException(sprintf('Operator %s is not valid, valid operators: %s', $operator, implode(', ', self::VALID_OPERATORS)));
        }

        $this->operator = $operator;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getOkMin(): mixed
    {
        return $this->okMin;
    }

    public function getOkMax(): mixed
    {
        return $this->okMax;
    }

    public function getWarnMin(): mixed
    {
        return $this->warnMin;
    }

    public function getWarnMax(): mixed
    {
        return $this->warnMax;
    }

    private function check(mixed $min, mixed $max, mixed $value): bool
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException(sprintf('Value %s is not numeric', $value));
        }

        if ($min !== null && $value < $min) {
            return false;
        }

        if ($max === null) {
            return true;
        }

        return $value <= $max;
    }
}
