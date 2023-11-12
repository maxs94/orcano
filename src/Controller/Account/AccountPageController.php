<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Account;

use App\Context\Context;
use App\Context\ContextLoader;
use App\Controller\Page\AbstractPageController;
use App\DataObject\Page\PageMessageDataObject;
use App\Service\Api\EntityUpsertService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountPageController extends AbstractPageController
{
    public function __construct(
        Context $context,
        private readonly EntityUpsertService $entityUpsertService,
        private readonly ContextLoader $contextLoader
    ) {
        parent::__construct($context);
    }

    #[Route('/account', name: 'account')]
    public function indexAction(Request $request): Response
    {
        $user = $this->context->getCurrentUser();
        if (!$user instanceof \App\Entity\User) {
            return $this->redirectToRoute('login');
        }

        $dataSaved = $this->processform($request, $user->getId());

        $response = $this->renderPage('account/index.html.twig');

        if ($dataSaved) {
            $response->headers->set('HX-Refresh', 'true');
        }

        return $response;
    }

    private function processForm(Request $request, int $id): bool
    {
        $errors = [];
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            if (empty($data['email'])) {
                $errors['email'] = new PageMessageDataObject('label.email-required', PageMessageDataObject::TYPE_DANGER);
            }

            if (!empty($data['password']) && $data['password'] !== $data['password-repeat']) {
                $errors['password'] = new PageMessageDataObject('label.password-not-equal', PageMessageDataObject::TYPE_DANGER);
            }

            $data['id'] = $id;

            try {
                $this->entityUpsertService->upsert('user', $data);
            } catch (\Exception $ex) {
                $this->addMessage($ex->getMessage(), PageMessageDataObject::TYPE_DANGER);
            }

            $this->setErrors($errors);

            if ($errors === []) {
                $this->addMessage('label.entity-saved', PageMessageDataObject::TYPE_SUCCESS);
                $this->contextLoader->update();

                return true;
            }
        }

        return false;
    }
}
