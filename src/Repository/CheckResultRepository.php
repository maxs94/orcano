<?php

namespace App\Repository;

use App\Entity\CheckResult;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CheckResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method CheckResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method CheckResult[]    findAll()
 * @method CheckResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckResultRepository extends AbstractServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CheckResult::class);
    }

//    /**
//     * @return CheckResult[] Returns an array of CheckResult objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CheckResult
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
