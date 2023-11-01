<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject\Page;

interface PageDataObjectInterface
{
    public function getTitle(): ?string;

    public function setTitle(?string $title): self;
}
