<?php

namespace App\Repository;

use App\Entity\ServiceCheckCondition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServiceCheckCondition>
 *
 * @method ServiceCheckCondition|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceCheckCondition|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceCheckCondition[]    findAll()
 * @method ServiceCheckCondition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceCheckConditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceCheckCondition::class);
    }

    public function getConditionsForServiceCheck(int $serviceCheckId): array
    {
        $qb = $this->createQueryBuilder('scc')
            ->select('scc')
            ->where('scc.serviceCheck = :serviceCheckId')
            ->setParameter('serviceCheckId', $serviceCheckId)
            ->orderBy('scc.id', 'ASC');

        return $qb->getQuery()->getResult();
    }


//    /**
//     * @return ServiceCheckCondition[] Returns an array of ServiceCheckCondition objects
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

//    public function findOneBySomeField($value): ?ServiceCheckCondition
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
