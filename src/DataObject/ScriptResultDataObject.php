<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject;

class ScriptResultDataObject implements DataObjectInterface
{
    public const RESULT_OK = 'OK';
    public const RESULT_WARNING = 'WARNING';
    public const RESULT_ERROR = 'ERROR';
    public const RESULT_UNKNOWN = 'UNKNOWN';

    private string $checkResult;

    /** @var array<string, mixed> */
    private array $message = [];

    /** @var array<string, mixed> */
    private array $scriptOutput = [];

    private ?string $note = null;

    public function getCheckResult(): string
    {
        return $this->checkResult;
    }

    public function setCheckResult(string $result): self
    {
        $this->checkResult = $result;

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

    /** @return array<string, mixed> */
    public function getScriptOutput(): array
    {
        return $this->scriptOutput;
    }

    /** @param array<string, mixed> $scriptOutput */
    public function setScriptOutput(array $scriptOutput): self
    {
        $this->scriptOutput = $scriptOutput;

        return $this;
    }
}
