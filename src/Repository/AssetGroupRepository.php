<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Repository;

use App\DataObject\Collection\DataObjectCollection;
use App\DataObject\Collection\DataObjectCollectionInterface;
use App\Entity\AssetGroup;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AssetGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetGroup[] findAll()
 * @method AssetGroup[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetGroupRepository extends AbstractServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssetGroup::class);
    }

    public function upsert(array $data): AssetGroup
    {
        $em = $this->getEntityManager();
        $assetGroup = $data['id'] !== 0 ? $this->find($data['id']) : new AssetGroup();

        $assetGroup->setName($data['name'] ?? '');

        $em->persist($assetGroup);
        $em->flush();

        return $assetGroup;
    }

    /**
     * @param array<string> $names
     */
    public function findByNamesAsCollection(array $names): DataObjectCollectionInterface
    {
        $result = $this->createQueryBuilder('a')
            ->andWhere('a.name IN (:names)')
            ->setParameter('names', $names)
            ->getQuery()
            ->getResult()
        ;

        return new DataObjectCollection($result);
    }

    //    /**
    //     * @return AssetGroup[] Returns an array of AssetGroup objects
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

    //    public function findOneBySomeField($value): ?AssetGroup
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
