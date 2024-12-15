<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Edit;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\Service\Page\CheckScriptParameterPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckScriptParameterPageController extends AbstractPageController
{
    public function __construct(
        Context $context,
        private readonly CheckScriptParameterPageLoader $checkScriptParameterPageLoader
    ) {
        parent::__construct($context);
    }

    #[Route('/edit/check-script/{id}/parameters', name: 'check_script_parameter', methods: ['GET'])]
    public function indexAction(Request $request, Context $context, int $id = null): Response
    {
        $page = $this->checkScriptParameterPageLoader->load($request, $context, $id);

        return $this->renderPage('edit/check-script-parameter.html.twig', ['page' => $page]);
    }
}
