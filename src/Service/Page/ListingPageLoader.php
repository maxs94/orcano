<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Page;

use App\Context\Context;
use App\DataObject\Page\ListingPageDataObject;
use App\DataObject\Page\PageDataObjectInterface;
use App\DataObject\PaginationDataObject;
use Symfony\Component\HttpFoundation\Request;

class ListingPageLoader extends AbstractPageLoader
{
    public function load(Request $request, string $entityName, Context $context): PageDataObjectInterface
    {
        $page = $request->query->getInt('page', 1);

        $repo = $this->getEntityRepository($entityName);

        $limit = $context->getCurrentUser()->getRowLimit();

        $result = $repo->getListing([], null, null, $limit, $page);

        $pagination = $this->createPagination($limit, $result->getTotalCount(), $page, $entityName);

        $title = $this->translator->trans('title.' . $entityName . '.listing');

        return (new ListingPageDataObject())
            ->setEntityName($entityName)
            ->setPage($page)
            ->setPagination($pagination)
            ->setResult($result)
            ->setTitle($title)
        ;
    }

    private function createPagination(int $limit, int $total, int $currentPage, string $entityName): PaginationDataObject
    {
        $pagination = new PaginationDataObject();

        $totalPages = ceil($total / $limit);

        $pagination->setTotalPages((int) $totalPages);
        $pagination->setCurrentPageNo($currentPage);

        $prevPageNo = $currentPage - 1;
        $nextPageNo = $currentPage + 1;

        $baseLink = '/listing/body/' . $entityName;

        if ($prevPageNo > 0) {
            $pagination->setPreviousLink($baseLink . '?page=' . $prevPageNo);
        }

        for ($i = 1; $i <= $totalPages; ++$i) {
            $pagination->addPageLink($i, $baseLink . '?page=' . $i);
        }

        if ($currentPage < $totalPages) {
            $pagination->setNextLink($baseLink . '?page=' . $nextPageNo);
        }

        return $pagination;
    }
}
