<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Page;

use App\Context\Context;
use App\DataObject\Page\ListingPageDataObject;
use Symfony\Component\HttpFoundation\Request;

class ListingPageLoader
{
    public function load(Request $request, string $entityName, Context $context): ListingPageDataObject
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', ListingPageDataObject::DEFAULT_LIMIT);

        return (new ListingPageDataObject())
            ->setEntityName($entityName)
            ->setPage($page)
            ->setLimit($limit)
        ;
    }
}
