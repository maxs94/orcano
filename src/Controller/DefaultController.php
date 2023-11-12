<?php

declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller;

use App\Controller\Page\AbstractPageController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractPageController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->renderPage('index.html.twig');
    }
}
