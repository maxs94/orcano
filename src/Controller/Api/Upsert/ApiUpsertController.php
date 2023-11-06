<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Api\Upsert;

use App\Controller\Api\AbstractApiController;
use App\Entity\ApiEntityInterface;
use App\Event\EntityUpsertEvent;
use App\Service\Converter\CaseConverter;
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

        /** @var ApiEntityInterface $entity */
        $entity = isset($data['id']) ? $repository->find($data['id']) : new $entityClassName();

        if (!$entity instanceof ApiEntityInterface) {
            return $this->json(['success' => false, 'message' => 'Entity does not implement ApiEntityInterface']);
        }

        $data = $this->hydrateEntities($data, $entity);

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

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function hydrateEntities(array $data, ApiEntityInterface $entity): array
    {
        $ret = [];

        foreach ($data as $key => $value) {
            $key = CaseConverter::kebabCaseToCamelCase($key);

            $reflectionClass = new \ReflectionClass($entity);
            $reflectionProperty = $reflectionClass->getProperty($key);

            /** @var \ReflectionNamedType $reflectionType */
            $reflectionType = $reflectionProperty->getType();
            $type = $reflectionType->getName();

            // if type is an object, try to get the related entity from database
            if (stristr($type, '\\')) {
                /** @phpstan-ignore-next-line */
                $repo = $this->em->getRepository($type);
                if ($repo == null) {
                    throw new \Exception("Repository {$type} not found");
                }

                $relatedEntity = $repo->find((int) $value);
                if ($relatedEntity === null) {
                    throw new \Exception("Entity {$type} with id {$value} not found");
                }
                $ret[$key] = $relatedEntity;
            } else {
                $ret[$key] = $value;
            }
        }

        return $ret;
    }
}
