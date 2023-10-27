<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject\Api;

use App\DataObject\DataObjectInterface;

class ApiInfoMessageDataObject implements DataObjectInterface
{
    public function __construct(
        private readonly string $message
    ) {}

    public function getMessage(): string
    {
        return $this->message;
    }
}
