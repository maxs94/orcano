<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Page;

use App\Context\Context;
use App\DataObject\Collection\DataObjectCollectionInterface;
use App\DataObject\Page\PageDataObject;
use App\DataObject\Page\PageDataObjectInterface;
use App\Entity\ServiceCheck;
use App\Repository\CheckScriptRepository;
use App\Repository\ServiceCheckRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class ServiceCheckPageLoader
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly CheckScriptRepository $checkScriptRepository,
        private readonly ServiceCheckRepository $serviceCheckRepository
    ) {}

    public function load(Request $request, Context $context, int $id = null): PageDataObjectInterface
    {
        $title = $this->translator->trans('title.service-check.edit');

        $checkScript = is_null($id) ? new ServiceCheck() : $this->getServiceCheck($id);

        return (new PageDataObject())
            ->setTitle($title)
            ->addParameter('checkScripts', $this->getAllCheckScripts())
            ->addParameter('serviceCheck', $checkScript)
        ;
    }

    private function getServiceCheck(int $id): ?ServiceCheck
    {
        return $this->serviceCheckRepository->find($id);
    }

    private function getAllCheckScripts(): DataObjectCollectionInterface
    {
        return $this->checkScriptRepository->findAllAsCollection();
    }
}
