<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Repository;

use App\Condition\Criteria;
use App\DataObject\Collection\DataObjectCollection;
use App\DataObject\Collection\DataObjectCollectionInterface;
use App\DataObject\Page\ListingPageDataObject;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

trait BaseRepositoryTrait
{
    public function findAllAsCollection(string $indexBy = null): DataObjectCollectionInterface
    {
        $result = $this->findAll();

        return new DataObjectCollection($result, $indexBy);
    }

    /**
     * Finds entities by a set of criteria.
     *
     * @param int|null $limit
     * @param int|null $offset
     *
     * @psalm-param array<string, mixed> $criteria
     * @psalm-param array<string, string>|null $orderBy
     */
    public function findByAsCollection(array $criteria, array $orderBy = null, string $indexBy = null, $limit = null, $offset = null): DataObjectCollectionInterface
    {
        $result = $this->findBy($criteria, $orderBy, $limit, $offset);

        return new DataObjectCollection($result, $indexBy);
    }

    /**
     * @param array<array<mixed>> $searches
     * @param array<string, string>|null $orderBy
     */
    public function getListing(
        array $searches = [],
        array $orderBy = null,
        string $indexBy = null,
        int $limit = ListingPageDataObject::DEFAULT_LIMIT,
        int $page = 1
    ): DataObjectCollectionInterface {
        $qb = $this->createQueryBuilder('a')->select('a');

        if ($orderBy !== null) {
            $field = key($orderBy);
            $direction = current($orderBy);
            $qb->orderBy('a.' . $field, $direction);
        }

        if ($searches !== []) {
            $this->buildSearch($searches, $qb);
        }

        $paginator = new Paginator($qb->getQuery(), true);
        $paginator = $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
        ;

        $results = $paginator->getResult();

        return new DataObjectCollection($results, $indexBy);
    }

    /** @param array<array<mixed>> $searches */
    private function buildSearch(array $searches, QueryBuilder $qb): void
    {
        foreach ($searches as $search) {
            $this->validateSearchArray($search);

            $criteria = new Criteria($search['field'], $search['operator'], $search['value']);

            switch ($criteria->getOperator()) {
                case 'equals':
                    $parameterName = uniqid('v');
                    $qb->andWhere($qb->expr()->eq('a.' . $criteria->getField(), ':' . $parameterName));
                    $qb->setParameter($parameterName, $criteria->getValue());
                    break;
                case 'contains':
                    $parameterName = uniqid('v');
                    $qb->andWhere($qb->expr()->like('a.' . $criteria->getField(), ':' . $parameterName));
                    $qb->setParameter($parameterName, '%' . $criteria->getValue() . '%');
                    break;
                case 'between':
                    $parameterName1 = uniqid('v');
                    $parameterName2 = uniqid('v');
                    $qb->andWhere($qb->expr()->between('a.' . $criteria->getField(), ':' . $parameterName1, ':' . $parameterName2));
                    $qb->setParameter($parameterName1, $criteria->getValues()[0]);
                    $qb->setParameter($parameterName2, $criteria->getValues()[1]);
                    break;
            }
        }
    }

    /** @param array<mixed> $search */
    private function validateSearchArray(array $search): void
    {
        if (count($search) !== 3) {
            throw new \InvalidArgumentException('Search array must have 3 elements');
        }

        if (!isset($search['operator'])) {
            throw new \InvalidArgumentException('Search array must have an operator');
        }

        if (!isset($search['field'])) {
            throw new \InvalidArgumentException('Search array must have a field');
        }

        if (!isset($search['value'])) {
            throw new \InvalidArgumentException('Search array must have value(s)');
        }
    }
}
