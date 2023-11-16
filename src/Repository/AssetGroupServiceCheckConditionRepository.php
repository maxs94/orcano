<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Repository;

use App\Entity\AssetGroupServiceCheckCondition;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AssetGroupServiceCheckCondition|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetGroupServiceCheckCondition|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetGroupServiceCheckCondition[] findAll()
 * @method AssetGroupServiceCheckCondition[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetGroupServiceCheckConditionRepository extends AbstractServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssetGroupServiceCheckCondition::class);
    }

    //    /**
    //     * @return AssetGroupServiceCheckCondition[] Returns an array of AssetGroupServiceCheckCondition objects
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

    //    public function findOneBySomeField($value): ?AssetGroupServiceCheckCondition
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
