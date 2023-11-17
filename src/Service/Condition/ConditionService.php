<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Condition;

use App\Condition\ConditionCollection;
use App\Condition\EqualsCondition;
use App\Condition\MinMaxCondition;
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

    /** @return array<array{class: string, parameters: array<array{name: string, optional: bool, type: string}>}> */
    public function getAllAvailableConditions(): array
    {
        $conditions = [];

        // TODO: get them from filesystem
        $availableConditions = [EqualsCondition::class, MinMaxCondition::class];

        foreach ($availableConditions as $className) {
            $reflectionClass = new \ReflectionClass($className);
            $parameters = $reflectionClass->getConstructor()->getParameters();

            $conditions[$className] = [
                'class' => $className,
                'name' => $reflectionClass->getShortName(),
                'parameters' => $this->buildParameters($parameters),
            ];
        }

        return $conditions;
    }

    /**
     * @param array<\ReflectionParameter> $parameters
     *
     * @return array<array{name: string, optional: bool, type: string}>
     */
    private function buildParameters(array $parameters): array
    {
        $result = [];

        /** @var \ReflectionParameter $parameter */
        foreach ($parameters as $parameter) {
            /** @var \ReflectionNamedType $parameterType */
            $parameterType = $parameter->getType();

            $result[] = [
                'name' => $parameter->getName(),
                'optional' => $parameter->isOptional(),
                'type' => $parameterType->getName(),
            ];
        }

        return $result;
    }
}
