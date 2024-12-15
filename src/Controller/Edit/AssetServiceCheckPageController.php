<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Edit;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\DataObject\Page\PageMessageDataObject;
use App\Repository\AssetServiceCheckRepository;
use App\Service\Page\AssetServiceCheckPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssetServiceCheckPageController extends AbstractPageController
{
    public function __construct(
        Context $context,
        private readonly AssetServiceCheckPageLoader $assetServiceCheckPageLoader,
        private readonly AssetServiceCheckRepository $assetServiceCheckRepository
    ) {
        parent::__construct($context);
    }

    #[Route('/edit/asset/{assetId}/asset-service-check/{id}', name: 'edit_asset_service_check', methods: ['GET', 'POST'])]
    public function indexAction(Request $request, Context $context, int $assetId = null, int $id = null): Response
    {
        $this->processForm($request, $id);

        $page = $this->assetServiceCheckPageLoader->load($request, $context, $assetId, $id);

        return $this->renderPage('edit/asset-service-check.html.twig', ['page' => $page]);
    }

    private function processForm(Request $request, int $id = null): void
    {
        $errors = [];
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            if (empty($data['name'])) {
                $errors['name'] = new PageMessageDataObject('alert.name-empty', PageMessageDataObject::TYPE_DANGER);
            }

            if (($data['service-check'] || $data['service-check'] === 0) === false) {
                $errors['asset_service_check'] = new PageMessageDataObject('alert.service-check-empty', PageMessageDataObject::TYPE_DANGER);
            }

            $data['id'] = $id ?? 0;

            if ($errors === []) {
                try {
                    $this->assetServiceCheckRepository->upsert($data);
                } catch (\Exception $ex) {
                    $this->addMessage($ex->getMessage(), PageMessageDataObject::TYPE_DANGER);
                }
            }

            $this->setErrors($errors);

            if ($errors === []) {
                $this->addMessage('label.entity-saved', PageMessageDataObject::TYPE_SUCCESS);
            }
        }
    }

}
