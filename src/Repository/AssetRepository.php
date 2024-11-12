<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Repository;

use App\Entity\Asset;
use App\Entity\AssetGroup;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Asset|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asset|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asset[] findAll()
 * @method Asset[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetRepository extends AbstractServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asset::class);
    }

    /** @param array<string, mixed> $data */
    public function upsert(array $data): Asset
    {
        $em = $this->getEntityManager();
        $asset = $data['id'] !== 0 ? $this->find($data['id']) : new Asset();

        $asset->setName($data['name'] ?? '');
        $asset->setHostname($data['hostname']);

        if (isset($data['ipv4-address'])) {
            $asset->setIpv4Address($data['ipv4-address']);
        }

        if (isset($data['ipv6-address'])) {
            $asset->setIpv6Address($data['ipv6-address']);
        }

        $asset->getAssetGroups()->clear();

        if (isset($data['asset-groups'])) {
            foreach ($data['asset-groups'] as $assetGroupId) {
                $assetGroup = $em->getRepository(AssetGroup::class)->find($assetGroupId);
                if ($assetGroup === null) {
                    throw new \Exception('AssetGroup not found');
                }
                $asset->addAssetGroup($assetGroup);
            }
        }

        $em->persist($asset);
        $em->flush();

        return $asset;
    }
    //    /**
    //     * @return Asset[] Returns an array of Asset objects
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

    //    public function findOneBySomeField($value): ?Asset
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
