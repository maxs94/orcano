<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Repository;

use App\Entity\ServiceCheck;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceCheck|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceCheck|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceCheck[] findAll()
 * @method ServiceCheck[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceCheckRepository extends AbstractServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceCheck::class);
    }

    //    /**
    //     * @return ServiceCheck[] Returns an array of ServiceCheck objects
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

    //    public function findOneBySomeField($value): ?ServiceCheck
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
