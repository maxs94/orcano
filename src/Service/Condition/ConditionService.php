<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Condition;

use App\Condition\ConditionCollection;
use App\Repository\AssetGroupServiceCheckConditionRepository;
use App\Repository\AssetRepository;

class ConditionService
{
    public function __construct(
        private readonly AssetGroupServiceCheckConditionRepository $assetGroupServiceCheckConditionRepository,
        private readonly AssetRepository $assetRepository,
    ) {}

    // TODO: inheritance from assets
    public function getCheckConditions(int $assetId, int $serviceCheckId): ConditionCollection
    {
        $asset = $this->assetRepository->find($assetId);
        $assetGroups = $asset->getAssetGroups();

        $ids = [];
        foreach ($assetGroups as $assetGroup) {
            $ids[] = $assetGroup->getId();
        }

        $conditions = $this->assetGroupServiceCheckConditionRepository->findBy([
            'assetGroup' => $ids,
            'serviceCheck' => $serviceCheckId,
        ]);

        if ($conditions === []) {
            throw new \Exception('Could not find any conditions for assetId ' . $assetId . ' and serviceCheckId ' . $serviceCheckId);
        }

        if (count($conditions) > 1) {
            throw new \Exception('Found more than one condition collection for assetId ' . $assetId . ' and serviceCheckId ' . $serviceCheckId . ' - this is not yet supported (which one has priority?)');
        }

        $result = unserialize($conditions[0]->getConditions());

        if ($result === false) {
            throw new \Exception('Could not unserialize conditions for assetId ' . $assetId . ' and serviceCheckId ' . $serviceCheckId);
        }

        return $result;
    }
}
