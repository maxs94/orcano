<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Condition;

class EqualsCondition extends AbstractCondition
{
    public function __construct(private mixed $okValue, private mixed $warnValue = null) {}

    /**
     * @return array<string, mixed>
     */
    public function __serialize(): array
    {
        return [
            'okValue' => $this->okValue,
            'warnValue' => $this->warnValue,
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public function __unserialize(array $data): void
    {
        $this->okValue = $data['okValue'];
        $this->warnValue = $data['warnValue'];
    }

    public function checkIfOk(mixed $value): bool
    {
        return $this->okValue === $value;
    }

    public function checkIfWarn(mixed $value): bool
    {
        return $this->warnValue === $value;
    }
}
