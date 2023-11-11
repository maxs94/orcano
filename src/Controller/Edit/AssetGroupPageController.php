<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Edit;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\Service\Page\AssetGroupPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssetGroupPageController extends AbstractPageController
{
    public function __construct(
        Context $context,
        private readonly AssetGroupPageLoader $assetGroupPageLoader
    ) {
        parent::__construct($context);
    }

    #[Route('/edit/asset-group/{id}', name: 'edit_asset_group')]
    public function indexAction(Request $request, Context $context, int $id = null): Response
    {
        $page = $this->assetGroupPageLoader->load($request, $context, $id);

        return $this->renderPage('edit/asset-group.html.twig', ['page' => $page]);
    }
}
