<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Api\Upsert;

use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiUpsertController extends AbstractApiController
{
    #[Route('/api/upsert/{entity}', name: 'api_upsert', methods: ['POST'])]
    public function upsertAction(Request $request, string $entity): JsonResponse
    {
        $data = $request->request->all();

        if (empty($data)) {
            $data = $this->getJsonContentFromRequest($request);
        }

        try {
            $this->entityUpsertService->upsert($entity, $data);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return $this->json(['success' => false, 'message' => $ex->getMessage()]);
        }

        $message = $this->translator->trans('label.entity-saved');

        return $this->getJson(null, $message);
    }

}
