<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Api;

use App\DataObject\Api\ApiInfoMessageDataObject;
use App\DataObject\Collection\DataObjectCollection;
use App\DataObject\Collection\DataObjectCollectionInterface;
use App\Repository\AbstractServiceEntityRepository;
use App\Service\Converter\CaseConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractApiController extends AbstractController
{
    private readonly DataObjectCollectionInterface $errors;

    private readonly DataObjectCollectionInterface $warnings;

    public function __construct(
        protected readonly EntityManagerInterface $em,
        protected readonly TranslatorInterface $translator,
        protected readonly EventDispatcherInterface $eventDispatcher
    ) {
        $this->errors = new DataObjectCollection();
        $this->warnings = new DataObjectCollection();
    }

    public function addError(string $message): void
    {
        $this->errors->add(new ApiInfoMessageDataObject($message));
    }

    public function addWarning(string $message): void
    {
        $this->warnings->add(new ApiInfoMessageDataObject($message));
    }

    /**
     * @return array<mixed>
     */
    public function getJsonContentFromRequest(Request $request): array
    {
        $content = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON');
        }

        return $content;
    }

    protected function getJson(DataObjectCollectionInterface $collection = null, string $message = null): JsonResponse
    {
        $res = [
            'success' => $this->errors->getCount() === 0,
            'errors' => $this->errors->getElements(),
            'warnings' => $this->errors->getElements(),
            'message' => $message,
            'data' => $collection instanceof \App\DataObject\Collection\DataObjectCollectionInterface ? $collection->getElements() : [],
            'count' => $collection instanceof \App\DataObject\Collection\DataObjectCollectionInterface ? $collection->getCount() : 0,
        ];

        return $this->json($res);
    }

    protected function getEntityRepository(string $entity): AbstractServiceEntityRepository
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
