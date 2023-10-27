<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Repository;

use App\Entity\ServiceCheckWorkerStats;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceCheckWorkerStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceCheckWorkerStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceCheckWorkerStats[] findAll()
 * @method ServiceCheckWorkerStats[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceCheckWorkerStatsRepository extends AbstractServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceCheckWorkerStats::class);
    }

    //    /**
    //     * @return ServiceCheckWorkerStats[] Returns an array of ServiceCheckWorker objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ServiceCheckWorkerStats
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
