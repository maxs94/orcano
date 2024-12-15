<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Repository;

use App\DataObject\Collection\DataObjectCollection;
use App\DataObject\Collection\DataObjectCollectionInterface;
use App\Entity\AssetGroup;
use App\Entity\ServiceCheck;
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

        $this->clearServiceChecks($assetGroup);

        if (isset($data['service-checks'])) {
            foreach ($data['service-checks'] as $serviceCheckId) {
                $serviceCheck = $em->getRepository(ServiceCheck::class)->find($serviceCheckId);
                if ($serviceCheck === null) {
                    throw new \Exception('Service check not found');
                }
                $assetGroup->addServiceCheck($serviceCheck);
            }
        }

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

    private function clearServiceChecks(AssetGroup $assetGroup): void
    {
        $assetGroup->getServiceChecks()->clear();

        $conn = $this->getEntityManager()->getConnection();
        $conn->delete('asset_group_service_check_condition', ['asset_group_id' => $assetGroup->getId()]);
        $conn->delete('service_check_asset_group', ['asset_group_id' => $assetGroup->getId()]);
    }
}
