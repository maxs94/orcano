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

    /** @var int|float */
    private mixed $warnMax = null;

    /** @var int|float */
    private mixed $okMax = null;

    /** @var int|float */
    private mixed $warnMin = null;

    /** @var int|float */
    private mixed $okMin = null;

    private string $operator = self::DEFAULT_OPERATOR;

    public function __construct(mixed $okMin, mixed $okMax, mixed $warnMin = null, mixed $warnMax = null)
    {
        $this->warnMin = $warnMin;
        $this->warnMax = $warnMax;

        $this->okMin = $okMin;
        $this->okMax = $okMax;
    }

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

    private function check(mixed $min, mixed $max, mixed $value): bool
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException(sprintf('Value %s is not numeric', $value));
        }

        if ($min !== null && $value < $min) {
            return false;
        }

        if ($max !== null && $value > $max) {
            return false;
        }

        return true;
    }
}
