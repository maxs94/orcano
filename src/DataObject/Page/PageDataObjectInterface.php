<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject\Page;

use Symfony\Component\HttpFoundation\ParameterBag;

interface PageDataObjectInterface
{
    public function getTitle(): ?string;

    public function setTitle(?string $title): self;
    
    public function getParameters(): ParameterBag;
    
    public function addParameter(string $key, mixed $value): self;
    
    public function getParameter(string $key): mixed;
}
