<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Edit;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\Service\Page\AssetServiceCheckParameterPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssetServiceCheckParameterPageController extends AbstractPageController
{
    public function __construct(
        Context $context,
        private readonly AssetServiceCheckParameterPageLoader $assetServiceCheckPageLoader
    ) {
        parent::__construct($context);
    }

    #[Route('/edit/asset/{assetId}/asset-service-check/{id}/parameters', name: 'edit_asset_service_check_parameter', methods: ['GET', 'POST'])]
    public function indexAction(Request $request, Context $context, int $assetId = null, int $id = null): Response
    {
        $page = $this->assetServiceCheckPageLoader->load($request, $context, $assetId, $id);

        return $this->renderPage('edit/asset-service-check-parameter.html.twig', ['page' => $page]);
    }
}
