<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Edit;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\DataObject\PageMessageDataObject;
use App\Service\Api\EntityUpsertService;
use App\Service\Page\ServiceCheckPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceCheckPageController extends AbstractPageController
{
    public function __construct(
        Context $context,
        private readonly ServiceCheckPageLoader $serviceCheckPageLoader,
        private readonly EntityUpsertService $entityUpsertService
    ) {
        parent::__construct($context);
    }

    #[Route('/edit/service-check/{id}', name: 'edit_service_check', methods: ['GET', 'POST'])]
    public function indexAction(Request $request, Context $context, int $id): Response
    {
        $page = $this->serviceCheckPageLoader->load($request, $context, $id);

        $this->processForm($request, $id);

        return $this->renderPage('edit/service-check.html.twig', ['page' => $page]);
    }

    private function processForm(Request $request, int $id): void 
    {
        $errors = [];
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            if (empty($data['name'])) {
                $errors['name'] = new PageMessageDataObject('alert.name-empty', PageMessageDataObject::TYPE_DANGER);
            }

            $request->request->set('id', $id);

            try {
                $this->entityUpsertService->upsert('serviceCheck', $request->request->all());
            } catch (\Exception $ex) {
                $this->addMessage($ex->getMessage(), PageMessageDataObject::TYPE_DANGER);
            }

            $this->setErrors($errors);

            if (count($errors) === 0) {
                $this->addMessage('label.entity-saved', PageMessageDataObject::TYPE_SUCCESS);
            }
        }
    }
}
