<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Edit;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\DataObject\Page\PageMessageDataObject;
use App\Repository\AssetRepository;
use App\Service\Page\AssetPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssetPageController extends AbstractPageController
{
    public function __construct(
        Context $context,
        private readonly AssetPageLoader $assetPageLoader,
        private readonly AssetRepository $assetRepository
    ) {
        parent::__construct($context);
    }

    #[Route('/edit/asset/{id}', name: 'edit_asset', methods: ['GET', 'POST'])]
    public function indexAction(Request $request, Context $context, int $id = null): Response
    {
        $this->processForm($request, $id);

        $page = $this->assetPageLoader->load($request, $context, $id);

        return $this->renderPage('edit/asset.html.twig', [
            'page' => $page,
        ]);
    }

    private function processForm(Request $request, int $id = null): void
    {
        $errors = [];
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            if (empty($data['hostname'])) {
                $errors['hostname'] = new PageMessageDataObject('alert.hostname-empty', PageMessageDataObject::TYPE_DANGER);
            }

            if (!empty($data['ipv4-address']) && !filter_var($data['ipv4-address'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $errors['ipv4_address'] = new PageMessageDataObject('alert.ipv4-address-invalid', PageMessageDataObject::TYPE_DANGER);
            }

            if (!empty($data['ipv6-address']) && !filter_var($data['ipv6-address'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $errors['ipv6_address'] = new PageMessageDataObject('alert.ipv6-address-invalid', PageMessageDataObject::TYPE_DANGER);
            }

            $data['id'] = $id ?? 0;

            if ($errors === []) {
                try {
                    $this->assetRepository->upsert($data);
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
