<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Repository;

use App\Condition\ConditionCollection;
use App\Entity\AssetServiceCheckCondition;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AssetServiceCheckCondition|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetServiceCheckCondition|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetServiceCheckCondition[] findAll()
 * @method AssetServiceCheckCondition[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetServiceCheckConditionRepository extends AbstractServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssetServiceCheckCondition::class);
    }

    public function upsertByIds(int $assetId, int $serviceCheckId, ConditionCollection $conditions): AssetServiceCheckCondition
    {
        $em = $this->getEntityManager();
        $assetServiceCheckCondition = $this->findOneBy(['asset' => $assetId, 'serviceCheck' => $serviceCheckId]) ?? new AssetServiceCheckCondition();

        $assetServiceCheckCondition->setAsset($em->getReference(\App\Entity\Asset::class, $assetId));
        $assetServiceCheckCondition->setServiceCheck($em->getReference(\App\Entity\ServiceCheck::class, $serviceCheckId));
        $assetServiceCheckCondition->setConditionCollection($conditions);

        $em->persist($assetServiceCheckCondition);
        $em->flush();

        return $assetServiceCheckCondition;
    }

    public function deleteByConditionId(int $assetId, int $serviceCheckId, string $conditionId): void
    {
        $em = $this->getEntityManager();
        $assetServiceCheckCondition = $this->findOneBy(['asset' => $assetId, 'serviceCheck' => $serviceCheckId]);
        if ($assetServiceCheckCondition === null) {
            throw new \Exception('Asset service check condition not found');
        }

        $conditionCollection = $assetServiceCheckCondition->getConditionCollection();
        $conditions = $conditionCollection->getConditions();
        if (isset($conditions[$conditionId])) {
            unset($conditions[$conditionId]);
            $conditionCollection->setConditions($conditions);
            $assetServiceCheckCondition->setConditionCollection($conditionCollection);
            $em->persist($assetServiceCheckCondition);
            $em->flush();
        }
    }
}
