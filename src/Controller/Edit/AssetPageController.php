<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Edit;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\Service\Page\AssetPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssetPageController extends AbstractPageController
{
    public function __construct(
        private readonly AssetPageLoader $assetPageLoader
    ) {}

    #[Route('/edit/asset/{id}', name: 'edit_asset')]
    public function indexAction(Request $request, Context $context, int $id = null): Response
    {
        $page = $this->assetPageLoader->load($request, $context, $id);

        return $this->renderPage($request, 'edit/asset.html.twig', ['page' => $page]);
    }
}
