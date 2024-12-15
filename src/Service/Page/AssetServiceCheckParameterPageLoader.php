<?php
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Page;

use App\Context\Context;
use App\DataObject\Page\PageDataObject;
use App\DataObject\Page\PageDataObjectInterface;
use App\Entity\AssetServiceCheck;
use App\Entity\ServiceCheck;
use App\Repository\AssetServiceCheckRepository;
use App\Repository\ServiceCheckRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class AssetServiceCheckParameterPageLoader
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly ServiceCheckRepository $serviceCheckRepository,
        private readonly AssetServiceCheckRepository $assetServiceCheckRepository
    ) {}

    public function load(Request $request, Context $context, int $assetId = null, int $assetServiceCheckId = null): PageDataObjectInterface
    {
        $title = $this->translator->trans('title.asset-service-check-parameters.edit');

        $assetServiceCheck = $assetServiceCheckId == 0 ? new AssetServiceCheck() : $this->getAssetServiceCheck($assetServiceCheckId);

        $page = new PageDataObject();
        $page->setTitle($title);

        $serviceCheckId = $request->query->getInt('service-check');
        if ($serviceCheckId === 0) {
            return $page;
        }

        $serviceCheck = $this->getServiceCheck($serviceCheckId);
        if ($serviceCheck === null) {
            throw new \RuntimeException('Service check not found');
        }

        $checkScript = $serviceCheck->getCheckScript();
        if ($checkScript === null) {
            throw new \RuntimeException('Check script not found');
        }

        $checkScriptParameter = $checkScript->getCheckScriptParameters();

        $page->addParameter('checkScriptParameter', $checkScriptParameter);
        $page->addParameter('assetServiceCheckConfig', $assetServiceCheck->getConfig());

        return $page;
    }

    private function getServiceCheck(int $id): ?ServiceCheck
    {
        return $this->serviceCheckRepository->find($id);
    }

    private function getAssetServiceCheck(int $id): ?AssetServiceCheck
    {
        return $this->assetServiceCheckRepository->find($id);
    }

}
