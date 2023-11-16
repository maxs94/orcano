<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Page;

use App\Context\Context;
use App\DataObject\Page\PageDataObject;
use App\DataObject\Page\PageDataObjectInterface;
use App\Entity\AssetGroup;
use App\Repository\AssetGroupRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class AssetGroupPageLoader
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly AssetGroupRepository $assetGroupRepository
    ) {}

    public function load(Request $request, Context $context, int $id = null): PageDataObjectInterface
    {
        $title = $this->translator->trans('title.asset-group.edit');

        $assetGroup = is_null($id) ? new AssetGroup() : $this->getAssetGroup($id);

        return (new PageDataObject())
            ->setTitle($title)
            ->addParameter('assetGroup', $assetGroup)
        ;
    }

    private function getAssetGroup(int $id): ?AssetGroup
    {
        return $this->assetGroupRepository->find($id);
    }
}
