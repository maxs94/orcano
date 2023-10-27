<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject;

class ScriptResultDataObject implements DataObjectInterface
{
    final public const RESULT_OK = 'OK';
    final public const RESULT_WARNING = 'WARNING';
    final public const RESULT_ERROR = 'ERROR';
    final public const RESULT_UNKNOWN = 'UNKNOWN';

    private string $result;

    /** @var array<string, mixed> */
    private array $message;

    private ?string $note = null;

    public function getResult(): string
    {
        return $this->result;
    }

    public function setResult(string $result): self
    {
        $this->result = $result;

        return $this;
    }

    /** @return array<string> */
    public function getMessage(): array
    {
        return $this->message;
    }

    /** @param array<string> $message */
    public function setMessage(array $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }
}
