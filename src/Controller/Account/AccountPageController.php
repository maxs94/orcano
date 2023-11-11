<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Account;

use App\Controller\Page\AbstractPageController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountPageController extends AbstractPageController
{
    #[Route('/account', name: 'account')]
    public function indexAction(): Response
    {
        return $this->renderPage('account/index.html.twig');
    }
}
