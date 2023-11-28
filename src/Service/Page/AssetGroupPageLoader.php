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
use App\Entity\AssetGroup;
use App\Repository\AssetGroupRepository;
use App\Repository\AssetGroupServiceCheckConditionRepository;
use App\Repository\ServiceCheckRepository;
use App\Service\Condition\ConditionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class AssetGroupPageLoader
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly AssetGroupRepository $assetGroupRepository,
        private readonly ServiceCheckRepository $serviceCheckRepository,
        private readonly AssetGroupServiceCheckConditionRepository $assetGroupServiceCheckConditionRepository,
        private readonly ConditionService $conditionService
    ) {}

    public function load(Request $request, Context $context, int $id = null): PageDataObjectInterface
    {
        $title = $this->translator->trans('title.asset-group.edit');

        $assetGroup = is_null($id) ? new AssetGroup() : $this->getAssetGroup($id);

        $assetGroupServiceCheckConditions = $this->assetGroupServiceCheckConditionRepository->findByAsCollection(['assetGroup' => $assetGroup], null, 'serviceCheckId');

        $availableConditions = $this->conditionService->getAllAvailableConditions();

        return (new PageDataObject())
            ->setTitle($title)
            ->addParameter('assetGroup', $assetGroup)
            ->addParameter('availableConditions', $availableConditions)
            ->addParameter('availableServiceChecks', $this->getAllServiceChecks())
            ->addParameter('assetGroupServiceCheckConditions', $assetGroupServiceCheckConditions)
        ;
    }

    private function getAssetGroup(int $id): ?AssetGroup
    {
        return $this->assetGroupRepository->find($id);
    }

    private function getAllServiceChecks(): DataObjectCollectionInterface
    {
        return $this->serviceCheckRepository->findAllAsCollection();
    }
}
