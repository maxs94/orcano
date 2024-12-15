<?php

namespace App\Repository;

use App\Condition\ConditionCollection;
use App\Entity\Asset;
use App\Entity\AssetServiceCheck;
use App\Entity\ServiceCheck;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AssetServiceCheck|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetServiceCheck|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetServiceCheck[]    findAll()
 * @method AssetServiceCheck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetServiceCheckRepository extends AbstractServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly ServiceCheckRepository $serviceCheckRepository,
        private readonly AssetRepository $assetRepository
    ) {
        parent::__construct($registry, AssetServiceCheck::class);
    }

    public function upsert(array $data): AssetServiceCheck
    {
        $em = $this->getEntityManager();
        $assetServiceCheck = $data['id'] !== 0 ? $this->find($data['id']) : new AssetServiceCheck();

        $assetServiceCheck->setName($data['name'] ?? '');

        $serviceCheck = $this->serviceCheckRepository->find($data['service-check']);
        if ($serviceCheck === null) {
            throw new \Exception('Service check not found');
        }

        $assetServiceCheck->setServiceCheck($serviceCheck);

        $asset = $this->assetRepository->find($data['assetId']);
        if ($asset === null) {
            throw new \Exception('Asset not found');
        }

        $assetServiceCheck->setAsset($asset);

        $assetServiceCheck->setConfig([
            'checkScriptParameter' => $data['check-script-parameter'] ?? [],
        ]);

        $em->persist($assetServiceCheck);
        $em->flush();

        return $assetServiceCheck;
    }

    public function upsertByIds(int $assetId, int $serviceCheckId, ConditionCollection $conditions): AssetServiceCheck
    {
        $em = $this->getEntityManager();
        $assetServiceCheckCondition = $this->findOneBy(['asset' => $assetId, 'serviceCheck' => $serviceCheckId]) ?? new AssetServiceCheck();

        $assetServiceCheckCondition->setAsset($em->getReference(Asset::class, $assetId));
        $assetServiceCheckCondition->setServiceCheck($em->getReference(ServiceCheck::class, $serviceCheckId));
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

//    /**
//     * @return AssetServiceCheck[] Returns an array of AssetServiceCheck objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AssetServiceCheck
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
