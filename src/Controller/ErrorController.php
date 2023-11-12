<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller;

use App\Controller\Page\AbstractPageController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractPageController
{
    #[Route('/404', name: 'page_not_found')]
    public function pageNotFound(): Response
    {
        return $this->renderPage('pages/404.html.twig');
    }
}
