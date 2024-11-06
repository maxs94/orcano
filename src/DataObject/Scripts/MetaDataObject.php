<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject\Scripts;

use App\DataObject\DataObjectInterface;
use App\Entity\CheckScriptParameter;

class MetaDataObject implements DataObjectInterface
{
    private string $filename;

    private string $name;

    private ?string $description = null;

    /** @var array<CheckScriptParameter> */
    private array $parameters = [];

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = trim($description);

        return $this;
    }

    /** @return array<CheckScriptParameter> */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function addParameter(CheckScriptParameter $parameter): self
    {
        $this->parameters[] = $parameter;

        return $this;
    }

    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }
}
