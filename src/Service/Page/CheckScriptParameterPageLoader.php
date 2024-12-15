<?php
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Page;

use App\Context\Context;
use App\DataObject\Page\PageDataObject;
use App\DataObject\Page\PageDataObjectInterface;
use App\Entity\CheckScript;
use App\Entity\ServiceCheck;
use App\Repository\CheckScriptRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class CheckScriptParameterPageLoader
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly CheckScriptRepository $checkScriptRepository
    ) {}

    public function load(Request $request, Context $context, int $checkScriptId = null): PageDataObjectInterface
    {
        $title = $this->translator->trans('title.service-check-parameters.edit');

        $checkScript = is_null($checkScriptId) ? new ServiceCheck() : $this->getCheckScript($checkScriptId);

        $page = new PageDataObject();
        $page->setTitle($title);

        $checkScriptParameter = $checkScript->getCheckScriptParameters();

        $page->addParameter('checkScriptParameter', $checkScriptParameter);

        return $page;
    }

    private function getCheckScript(int $id): ?CheckScript
    {
        return $this->checkScriptRepository->find($id);
    }

}
