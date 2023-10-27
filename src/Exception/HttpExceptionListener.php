<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class HttpExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (strpos($event->getRequest()->getRequestUri(), '/api/') === false) {
            return;
        }

        $response = new JsonResponse(
            [
                'success' => false,
                'errors' => [
                    $exception->getMessage(),
                ],
            ],
        );

        $response->setStatusCode(500);
        $event->setResponse($response);
    }
}
