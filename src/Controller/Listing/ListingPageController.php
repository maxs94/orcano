<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Listing;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\Service\Page\ListingPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListingPageController extends AbstractPageController
{
    public function __construct(
        private readonly ListingPageLoader $listingPageLoader
    ) {}

    #[Route('/listing/{entity}', name: 'listing')]
    public function listingAction(Request $request, string $entity, Context $context): Response
    {
        $page = $this->listingPageLoader->load($request, $entity, $context);

        $template = sprintf('listing/%s.html.twig', $entity);

        return $this->renderPage($request, $template, ['page' => $page]);
    }
}
