<?php

namespace App\Repository;

use App\Entity\CheckScriptParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CheckScriptParameter>
 *
 * @method CheckScriptParameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method CheckScriptParameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method CheckScriptParameter[]    findAll()
 * @method CheckScriptParameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckScriptParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CheckScriptParameter::class);
    }

//    /**
//     * @return CheckScriptParameter[] Returns an array of CheckScriptParameter objects
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

//    public function findOneBySomeField($value): ?CheckScriptParameter
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
