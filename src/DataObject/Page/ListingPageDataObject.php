<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject\Page;

use App\DataObject\Collection\DataObjectCollectionInterface;
use App\DataObject\PaginationDataObject;

class ListingPageDataObject extends AbstractPageDataObject
{
    public const DEFAULT_LIMIT = 25;

    private string $entityName;

    private int $page = 1;

    private DataObjectCollectionInterface $result;

    private ?PaginationDataObject $pagination = null;

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

    public function getResult(): DataObjectCollectionInterface
    {
        return $this->result;
    }

    public function setResult(DataObjectCollectionInterface $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getPagination(): ?PaginationDataObject
    {
        return $this->pagination;
    }

    public function setPagination(?PaginationDataObject $pagination): self
    {
        $this->pagination = $pagination;

        return $this;
    }
}
