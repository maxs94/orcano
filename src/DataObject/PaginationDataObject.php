<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataObject;

class PaginationDataObject implements DataObjectInterface
{
    private ?string $previousLink = null;

    private ?string $nextLink = null;

    private int $currentPageNo = 1;

    private int $totalPages = 0;

    /** @var array<int, string> */
    private array $pageLinks = [];

    public function getPreviousLink(): ?string
    {
        return $this->previousLink;
    }

    public function setPreviousLink(?string $previousLink): self
    {
        $this->previousLink = $previousLink;

        return $this;
    }

    public function getNextLink(): ?string
    {
        return $this->nextLink;
    }

    public function setNextLink(?string $nextLink): self
    {
        $this->nextLink = $nextLink;

        return $this;
    }

    public function addPageLink(int $pageNo, string $link): self
    {
        $this->pageLinks[$pageNo] = $link;

        return $this;
    }

    /** @return array<int, string> */
    public function getPageLinks(): array
    {
        return $this->pageLinks;
    }

    public function getCurrentPageNo(): int
    {
        return $this->currentPageNo;
    }

    public function setCurrentPageNo(int $currentPageNo): self
    {
        $this->currentPageNo = $currentPageNo;

        return $this;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function setTotalPages(int $totalPages): self
    {
        $this->totalPages = $totalPages;

        return $this;
    }

    public function getNextPageNo(): int
    {
        return $this->currentPageNo + 1;
    }

    public function getPreviousPageNo(): int
    {
        return $this->currentPageNo - 1;
    }
}
