<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Repository;

use App\Entity\CheckScript;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CheckScript|null find($id, $lockMode = null, $lockVersion = null)
 * @method CheckScript|null findOneBy(array $criteria, array $orderBy = null)
 * @method CheckScript[] findAll()
 * @method CheckScript[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckScriptRepository extends OrcanoServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CheckScript::class);
    }

    public function findByFilehash(string $filehash): ?CheckScript
    {
        return $this->findOneBy(['filehash' => $filehash]);
    }

    //    /**
    //     * @return CheckScript[] Returns an array of CheckCommand objects
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

    //    public function findOneBySomeField($value): ?CheckScript
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
