<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Account;

use App\Controller\Page\AbstractPageController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractPageController
{
    #[Route('/account', name: 'account')]
    public function indexAction(Request $request): Response
    {
        return $this->renderPage($request, 'account/index.html.twig');
    }
}
