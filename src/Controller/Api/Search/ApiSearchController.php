<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Api\Search;

use App\Controller\Api\AbstractApiController;
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
}
