<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Condition;

interface ConditionInterface
{
    public function checkIfOk(mixed $value): bool;

    public function checkIfWarn(mixed $value): bool;

    public function getConditionClassName(): string;

    /** @return array<string> */
    public function getParameters(): array;
    
    public function get(string $parameterName): mixed; 
}
