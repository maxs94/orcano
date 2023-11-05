<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Entity;

interface ApiEntityInterface
{
    /** @param array<string, mixed> $data */
    public function setData(array $data): self;
}
