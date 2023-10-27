<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Api\Search;

use App\Controller\Api\AbstractApiController;
use App\Repository\AbstractServiceEntityRepository;
use App\Service\Converter\CaseConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiSearchController extends AbstractApiController
{
    #[Route('/api/search/{entity}', name: 'api_search', methods: ['POST'])]
    public function searchAction(Request $request, string $entity): JsonResponse
    {
        $repository = $this->getEntityRepository($entity);

        $data = $this->getJsonContentFromRequest($request);

        $search = $data['search'] ?? [];
        $orderBy = $data['orderBy'] ?? null;
        $indexBy = $data['indexBy'] ?? null;
        $limit = $data['limit'] ?? null;
        $page = $data['page'] ?? null;

        $results = $repository->getListing($search, $orderBy, $indexBy, $limit, $page);

        return $this->getJson($results);
    }

    private function getEntityRepository(string $entity): AbstractServiceEntityRepository
    {
        $entityName = CaseConverter::kebabCaseToCamelCase($entity);
        $entityClassName = sprintf('App\Entity\%s', ucfirst($entityName));

        if (!class_exists($entityClassName)) {
            throw new \Exception(sprintf('Repository class not found for entity: %s', $entity));
        }

        /** @var AbstractServiceEntityRepository $repository */
        $repository = $this->em->getRepository($entityClassName);

        return $repository;
    }
}
