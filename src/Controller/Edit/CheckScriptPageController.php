<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Edit;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\DataObject\Page\PageMessageDataObject;
use App\Repository\CheckScriptRepository;
use App\Service\Api\EntityUpsertService;
use App\Service\Page\CheckScriptPageLoader;
use App\Service\Scripts\ScriptsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckScriptPageController extends AbstractPageController
{
    public function __construct(
        Context $context,
        private readonly CheckScriptPageLoader $checkScriptPageLoader,
        private readonly EntityUpsertService $entityUpsertService,
        private readonly CheckScriptRepository $checkScriptRepository,
        private readonly ScriptsService $scriptsService
    ) {
        parent::__construct($context);
    }

    #[Route('/edit/check-script/{id}', name: 'edit_check_script', methods: ['GET', 'POST'])]
    public function indexAction(Request $request, Context $context, int $id): Response
    {
        $page = $this->checkScriptPageLoader->load($request, $context, $id);

        $this->processForm($request, $id);

        return $this->renderPage('edit/check-script.html.twig', ['page' => $page]);
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
                $this->entityUpsertService->upsert('checkScript', $request->request->all());
            } catch (\Exception $ex) {
                $this->addMessage($ex->getMessage(), PageMessageDataObject::TYPE_DANGER);
            }

            if (!empty($data['scriptContent'])) {
                $this->saveCheckScriptContent($id, $data['scriptContent']);
            }

            $this->setErrors($errors);

            if (count($errors) === 0) {
                $this->addMessage('label.entity-saved', PageMessageDataObject::TYPE_SUCCESS);
            }
        }
    }

    private function saveCheckScriptContent(int $id, ?string $content = null): void
    {
        if ($content === null) {
            return;
        }

        $checkScript = $this->checkScriptRepository->find($id);
        if ($checkScript === null) {
            throw new \Exception('Check script not found');
        }

        $filename = $checkScript->getFilename();

        $this->scriptsService->setScriptContent($filename, $content);
        $this->scriptsService->refreshScripts();
    }
}
