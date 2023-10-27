<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Message;

use App\DataObject\ScriptResultDataObject;

class CheckResultNotification
{
    public function __construct(
        private readonly ScriptResultDataObject $result,
        private readonly CheckNotification $originalNotification
    ) {}

    public function getResult(): ScriptResultDataObject
    {
        return $this->result;
    }

    public function getOriginalNotification(): CheckNotification
    {
        return $this->originalNotification;
    }
}
