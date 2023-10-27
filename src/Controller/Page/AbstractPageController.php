<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Page;

use App\Context\Context;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractPageController extends AbstractController
{
    private ?Context $context = null;

    /**
     * @param array<string, mixed> $parameters
     */
    protected function renderPage(Request $request, string $view, array $parameters = [], Response $response = null): Response
    {
        $parameters['context'] = $this->getContext($request);

        return $this->render($view, $parameters, $response);
    }

    private function getContext(Request $request): Context
    {
        if (!$this->context instanceof \App\Context\Context) {
            $this->context = Context::createContextFromRequest($request);
        }

        return $this->context;
    }
}
