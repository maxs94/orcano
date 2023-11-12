<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Page;

use App\Context\Context;
use App\DataObject\Page\PageDataObject;
use App\DataObject\Page\PageDataObjectInterface;
use App\Entity\CheckScript;
use App\Repository\CheckScriptRepository;
use App\Service\Scripts\ScriptsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class CheckScriptPageLoader
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly CheckScriptRepository $checkScriptRepository,
        private readonly ScriptsService $scriptsService
    ) {}

    public function load(Request $request, Context $context, int $id = null): PageDataObjectInterface
    {
        $title = $this->translator->trans('title.check-script.edit');

        return (new PageDataObject())
            ->setTitle($title)
            ->addParameter('checkScript', $this->getCheckScript($id))
            ->addParameter('scriptContent', $this->scriptsService->getScriptContent($this->getCheckScript($id)->getFilename()))
        ;
    }

    private function getCheckScript(int $id): ?CheckScript
    {
        return $this->checkScriptRepository->find($id);
    }
}
