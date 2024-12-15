<?php
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Detail;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\Service\Page\CheckResultPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckResultPageController extends AbstractPageController
{
    public function __construct(
        Context $context,
        private readonly CheckResultPageLoader $checkResultPageLoader
    ) {
        parent::__construct($context);
    }

    #[Route('/detail/check-result/{id}', name: 'detail_check_result', methods: ['GET'])]
    public function indexAction(Request $request, Context $context, int $id = null): Response
    {
        $page = $this->checkResultPageLoader->load($request, $context, $id);

        return $this->renderPage('detail/check-result.html.twig', [
            'page' => $page,
        ]);
    }
}
