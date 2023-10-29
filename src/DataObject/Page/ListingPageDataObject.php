<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject\Page;

class ListingPageDataObject extends AbstractPageDataObject
{
    public const DEFAULT_LIMIT = 25;

    private string $entityName;

    private int $page = 1;

    private int $limit = self::DEFAULT_LIMIT;

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function setEntityName(string $entityName): self
    {
        $this->entityName = $entityName;

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page = 1): self
    {
        $this->page = $page;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit = self::DEFAULT_LIMIT): self
    {
        $this->limit = $limit;

        return $this;
    }
}
