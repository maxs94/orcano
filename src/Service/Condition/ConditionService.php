<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Condition;

use App\Condition\AbstractCondition;
use App\Condition\ConditionCollection;
use App\Condition\EqualsCondition;
use App\Condition\MinMaxCondition;
use App\Repository\AssetGroupServiceCheckConditionRepository;
use App\Repository\AssetRepository;
use App\Repository\AssetServiceCheckRepository;

class ConditionService
{
    public function __construct(
        private readonly AssetGroupServiceCheckConditionRepository $assetGroupServiceCheckConditionRepository,
        private readonly AssetServiceCheckRepository $assetServiceCheckRepository,
        private readonly AssetRepository $assetRepository,
    ) {}

    public function getCheckConditions(int $assetId, int $assetServiceCheckId): ConditionCollection
    {
        $assetServiceCheck = $this->assetServiceCheckRepository->find($assetServiceCheckId);
        if ($assetServiceCheck === null) {
            throw new \Exception('Asset service check not found');
        }

        $conditions = $assetServiceCheck->getConditionCollection();

        // if asset has no conditions for this service check, check asset groups
        if ($conditions->getCount() === 0) {
            $asset = $this->assetRepository->find($assetId);
            $assetGroups = $asset->getAssetGroups();

            $assetGroupIds = [];
            foreach ($assetGroups as $assetGroup) {
                $assetGroupIds[] = $assetGroup->getId();
            }

            $conditions = $this->assetGroupServiceCheckConditionRepository->findBy([
                'assetGroup' => $assetGroupIds,
                'serviceCheck' => $assetServiceCheck->getServiceCheck()->getId(),
            ]);
        }

        if ($conditions === []) {
            return new ConditionCollection();
        }

        if (count($conditions) > 1) {
            throw new \Exception('Found more than one condition collection for assetId ' . $assetId . ' and assetServiceCheckId ' . $assetServiceCheckId . ' - this is not yet supported (which one has priority?)');
        }

        $result = unserialize($conditions[0]->getConditions());

        if ($result === false) {
            throw new \Exception('Could not unserialize conditions for assetId ' . $assetId . ' and assetServiceCheckId ' . $assetServiceCheckId);
        }

        return $result;
    }

    /** @return array<AbstractCondition> */
    public function getAllAvailableConditions(): array
    {
        $conditions = [];

        // TODO: get them from filesystem
        $availableConditions = [EqualsCondition::class, MinMaxCondition::class];

        foreach ($availableConditions as $className) {
            $conditions[$className] = new $className();
        }

        return $conditions;
    }
}
