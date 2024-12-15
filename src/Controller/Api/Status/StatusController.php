<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Api\Status;

use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends AbstractApiController
{
    #[Route('/api/status', name: 'api_status', methods: ['GET'])]
    public function statusAction(): JsonResponse
    {
        return $this->getJson();
    }
}

