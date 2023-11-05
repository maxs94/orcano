<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject\Page;

use App\DataObject\DataObjectInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class AbstractPageDataObject implements PageDataObjectInterface, DataObjectInterface
{
    private ?string $title = null;

    private ?ParameterBag $parameters = null;

    public function getParameters(): ParameterBag
    {
        return $this->parameters;
    }

    public function addParameter(string $key, mixed $value): self
    {
        if (!$this->parameters instanceof \Symfony\Component\HttpFoundation\ParameterBag) {
            $this->parameters = new ParameterBag();
        }

        $this->parameters->set($key, $value);

        return $this;
    }

    public function getParameter(string $key): mixed
    {
        return $this->parameters->get($key);
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
