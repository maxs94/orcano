<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Api;

use App\DataObject\Api\ApiInfoMessageDataObject;
use App\DataObject\Collection\DataObjectCollection;
use App\DataObject\Collection\DataObjectCollectionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractApiController extends AbstractController
{
    private DataObjectCollectionInterface $errors;

    private DataObjectCollectionInterface $warnings;

    public function __construct(
        protected readonly EntityManagerInterface $em
    ) {
        $this->errors = new DataObjectCollection();
        $this->warnings = new DataObjectCollection();
    }

    public function getJson(DataObjectCollectionInterface $collection = null): JsonResponse
    {
        $res = [
            'success' => $this->errors->getCount() === 0,
            'errors' => $this->errors->getElements(),
            'warnings' => $this->errors->getElements(),
            'data' => $collection !== null ? $collection->getElements() : [],
            'count' => $collection !== null ? $collection->getCount() : 0,
        ];

        return $this->json($res);
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
}
