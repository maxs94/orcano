<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Edit;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\DataObject\Page\PageMessageDataObject;
use App\Repository\AssetGroupRepository;
use App\Service\Page\AssetGroupPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssetGroupPageController extends AbstractPageController
{
    public function __construct(
        Context $context,
        private readonly AssetGroupPageLoader $assetGroupPageLoader,
        private readonly AssetGroupRepository $assetGroupRepository
    ) {
        parent::__construct($context);
    }

    #[Route('/edit/asset-group/{id}', name: 'edit_asset_group')]
    public function indexAction(Request $request, Context $context, int $id = null): Response
    {
        $this->processForm($request, $id);

        $page = $this->assetGroupPageLoader->load($request, $context, $id);

        return $this->renderPage('edit/asset-group.html.twig', ['page' => $page]);
    }

    private function processForm(Request $request, int $id = null): void
    {
        $errors = [];
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            if (empty($data['name'])) {
                $errors['name'] = new PageMessageDataObject('label.name-required', PageMessageDataObject::TYPE_DANGER);
            }

            $data['id'] = $id ?? 0;

            if ($errors === []) {
                try {
                    $this->assetGroupRepository->upsert($data);
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
