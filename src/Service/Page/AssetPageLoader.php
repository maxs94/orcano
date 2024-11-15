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
use App\Repository\AssetServiceCheckConditionRepository;
use App\Repository\AssetServiceCheckRepository;
use App\Repository\ServiceCheckRepository;
use App\Service\Condition\ConditionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class AssetPageLoader
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly AssetRepository $assetRepository,
        private readonly AssetGroupRepository $assetGroupRepository,
        private readonly ServiceCheckRepository $serviceCheckRepository,
        private readonly AssetServiceCheckRepository $assetServiceCheckRepository,
        private readonly ConditionService $conditionService
    ) {}

    public function load(Request $request, Context $context, int $id = null): PageDataObjectInterface
    {
        $title = $this->translator->trans('title.asset.edit');

        $asset = is_null($id) ? new Asset() : $this->getAsset($id);

        return (new PageDataObject())
            ->setTitle($title)
            ->addParameter('asset', $asset)
            ->addParameter('availableAssetGroups', $this->getAllAssetGroups())
            ->addParameter('availableServiceChecks', $this->getAllServiceChecks())
            ->addParameter('availableConditions', $this->conditionService->getAllAvailableConditions())
            ->addParameter('assetServiceCheckConditions', $this->getAssetServiceCheckConditions($asset))
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

    private function getAllServiceChecks(): DataObjectCollectionInterface
    {
        return $this->serviceCheckRepository->findAllAsCollection();
    }

    private function getAssetServiceCheckConditions(Asset $asset): DataObjectCollectionInterface
    {
        return $this->assetServiceCheckRepository->findByAsCollection(['asset' => $asset], null, 'serviceCheckId');
    }

}
