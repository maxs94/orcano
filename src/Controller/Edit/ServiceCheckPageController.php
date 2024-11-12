<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Edit;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\DataObject\Page\PageMessageDataObject;
use App\Repository\ServiceCheckRepository;
use App\Service\Page\ServiceCheckPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceCheckPageController extends AbstractPageController
{
    public function __construct(
        Context $context,
        private readonly ServiceCheckPageLoader $serviceCheckPageLoader,
        private readonly ServiceCheckRepository $serviceCheckRepository
    ) {
        parent::__construct($context);
    }

    #[Route('/edit/service-check/{id}', name: 'edit_service_check', methods: ['GET', 'POST'])]
    public function indexAction(Request $request, Context $context, int $id = null): Response
    {
        $this->processForm($request, $id);

        $page = $this->serviceCheckPageLoader->load($request, $context, $id);

        return $this->renderPage('edit/service-check.html.twig', ['page' => $page]);
    }

    private function processForm(Request $request, int $id = null): void
    {
        $errors = [];
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            if (empty($data['name'])) {
                $errors['name'] = new PageMessageDataObject('alert.name-empty', PageMessageDataObject::TYPE_DANGER);
            }

            if (($data['check-script'] || $data['check-script'] === 0) === false) {
                $errors['check_script'] = new PageMessageDataObject('alert.check-script-empty', PageMessageDataObject::TYPE_DANGER);
            }

            $data['id'] = $id ?? 0;

            if ($errors === []) {
                try {
                    $this->serviceCheckRepository->upsert($data);
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
