<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Page;

use App\Context\Context;
use App\DataObject\Page\ListingPageDataObject;
use App\DataObject\Page\PageDataObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListingPageLoader
{
    public function __construct(private readonly TranslatorInterface $translator) { }

    public function load(Request $request, string $entityName, Context $context): PageDataObjectInterface
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', ListingPageDataObject::DEFAULT_LIMIT);

        $title = $this->translator->trans('title.' . $entityName . '.listing');

        return (new ListingPageDataObject())
            ->setEntityName($entityName)
            ->setPage($page)
            ->setLimit($limit)
            ->setTitle($title)
        ;
    }
}
