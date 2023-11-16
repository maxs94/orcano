<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Repository;

use App\Entity\CheckScript;
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

    public function upsert(array $data): ServiceCheck
    {
        $em = $this->getEntityManager();
        $serviceCheck = $data['id'] !== 0 ? $this->find($data['id']) : new ServiceCheck();

        $serviceCheck->setName($data['name'] ?? '');
        $serviceCheck->setCheckIntervalSeconds(empty($data['check-interval-seconds']) ? ServiceCheck::DEFAULT_CHECK_INTERVAL : (int) $data['check-interval-seconds']);
        $serviceCheck->setRetryIntervalSeconds(empty($data['retry-interval-seconds']) ? ServiceCheck::DEFAULT_CHECK_INTERVAL : (int) $data['retry-interval-seconds']);
        $serviceCheck->setMaxRetries(empty($data['max-retries']) ? ServiceCheck::DEFAULT_MAX_RETRIES : (int) $data['max-retries']);
        $serviceCheck->setNotificationsEnabled(isset($data['notifications-enabled']));
        $serviceCheck->setEnabled(isset($data['enabled']));

        $checkScriptRepository = $em->getRepository(CheckScript::class);
        $checkScript = $checkScriptRepository->find($data['check-script']);
        if ($checkScript === null) {
            throw new \Exception('Check script not found');
        }

        $serviceCheck->setCheckScript($checkScript);

        $em->persist($serviceCheck);
        $em->flush();

        return $serviceCheck;
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
