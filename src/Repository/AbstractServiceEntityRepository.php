<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Repository;

use App\DataObject\DataObjectInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractServiceEntityRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    /** @param array<string, mixed> $data */
    public function upsert(array $data): DataObjectInterface
    {
        throw new \Exception('Method upsert not implemented');
    }
}
