<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Api\Upsert;

use App\Controller\Api\AbstractApiController;
use App\Event\EntityUpsertEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiUpsertController extends AbstractApiController
{
    #[Route('/api/upsert/{entity}', name: 'api_upsert', methods: ['POST'])]
    public function upsertAction(Request $request, string $entity): JsonResponse
    {
        $data = $this->getJsonContentFromRequest($request);

        $repository = $this->getEntityRepository($entity);

        $entityClassName = $repository->getClassName();

        $entity = isset($data['id']) ? $repository->find($data['id']) : new $entityClassName();

        if (!method_exists($entity, 'setData')) {
            return $this->json(['success' => false, 'message' => 'Method setData not found on entity']);
        }

        $entity->setData($data);

        $this->em->persist($entity);
        $this->em->flush();

        $message = $this->translator->trans('label.entity-saved');

        $this->eventDispatcher->dispatch(new EntityUpsertEvent($entity));

        return $this->getJson(null, $message);
    }
}
