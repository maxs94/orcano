<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Page;

use App\Context\Context;
use App\DataObject\Collection\DataObjectCollectionInterface;
use App\DataObject\Page\PageDataObject;
use App\DataObject\Page\PageDataObjectInterface;
use App\Entity\Asset;
use App\Repository\AssetGroupRepository;
use App\Repository\AssetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class AssetPageLoader
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly AssetRepository $assetRepository,
        private readonly AssetGroupRepository $assetGroupRepository
    ) {}

    public function load(Request $request, Context $context, int $id = null): PageDataObjectInterface
    {
        $title = $this->translator->trans('title.asset.edit');

        $asset = is_null($id) ? new Asset() : $this->getAsset($id);

        return (new PageDataObject())
            ->setTitle($title)
            ->addParameter('asset', $asset)
            ->addParameter('availableAssetGroups', $this->getAllAssetGroups())
        ;
    }

    private function getAsset(int $id): ?Asset
    {
        return $this->assetRepository->find($id);
    }

    private function getAllAssetGroups(): DataObjectCollectionInterface
    {
        return $this->assetGroupRepository->findAllAsCollection();
    }
}
