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

        $search = $request->query->getString('search');
        if (!empty($search)) {
            $searches = json_decode($search, true, 512, JSON_THROW_ON_ERROR);
        } else {
            $searches = [];
        }

        $result = $repo->getListing($searches, null, null, $limit, $page);

        $pagination = $this->createPagination($limit, $result->getTotalCount(), $page, $entityName);

        $title = $this->translator->trans('title.' . $entityName . '.listing');

        $listingPageDataObject = (new ListingPageDataObject())
            ->setEntityName($entityName)
            ->setPage($page)
            ->setPagination($pagination)
            ->setResult($result)
            ->setTitle($title)
        ;

        $this->addQueryParametersToPage($request, $listingPageDataObject);

        return $listingPageDataObject;
    }

    private function addQueryParametersToPage(Request $request, PageDataObjectInterface $page): void
    {
        foreach ($request->query->all() as $key => $value) {
            $page->addParameter($key, $value);
        }
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
