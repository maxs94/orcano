<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Edit;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\Service\Page\ServiceCheckPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceCheckPageController extends AbstractPageController
{
    public function __construct(
        private readonly ServiceCheckPageLoader $serviceCheckPageLoader
    ) {}

    #[Route('/edit/service-check/{id}', name: 'edit_service_check')]
    public function indexAction(Request $request, Context $context, int $id = null): Response
    {
        $page = $this->serviceCheckPageLoader->load($request, $context, $id);

        return $this->renderPage($request, 'edit/service-check.html.twig', ['page' => $page]);
    }
}
