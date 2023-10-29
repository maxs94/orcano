<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Condition;

class Criteria
{
    public const VALID_OPERATORS = [
        'equals',
        'contains',
        'between',
    ];

    /** @param array<mixed> $values */
    public function __construct(
        private readonly string $field,
        private readonly string $operator,
        private readonly mixed $value = null,
        private readonly ?array $values = null,
    ) {
        $this->validateOperator($operator);
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    /** @return array<mixed> */
    public function getValues(): array
    {
        return $this->values;
    }

    public function validateOperator(string $operator): void
    {
        if (!in_array($operator, self::VALID_OPERATORS)) {
            throw new \InvalidArgumentException(sprintf('Invalid operator "%s"', $operator));
        }
    }
}
