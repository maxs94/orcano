<?php
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Page;

use App\Context\Context;
use App\DataObject\Collection\DataObjectCollectionInterface;
use App\DataObject\Page\PageDataObject;
use App\DataObject\Page\PageDataObjectInterface;
use App\Entity\AssetServiceCheck;
use App\Repository\AssetServiceCheckRepository;
use App\Repository\ServiceCheckRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class AssetServiceCheckPageLoader
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly AssetServiceCheckRepository $assetServiceCheckRepository,
        private readonly ServiceCheckRepository $serviceCheckRepository
    ) {}

    public function load(Request $request, Context $context, int $assetId = null, int $id = null): PageDataObjectInterface
    {
        $title = $this->translator->trans('title.asset-service-check.edit');

        $assetServiceCheck = is_null($id) ? new AssetServiceCheck() : $this->getAssetServiceCheck($id);

        return (new PageDataObject())
            ->setTitle($title)
            ->addParameter('assetServiceCheck', $assetServiceCheck)
            ->addParameter('availableServiceChecks', $this->getAvailableServiceChecks())
            ->addParameter('assetId', $assetId)
        ;
    }

    private function getAssetServiceCheck(int $id): ?AssetServiceCheck
    {
        return $this->assetServiceCheckRepository->find($id);
    }

    private function getAvailableServiceChecks(): DataObjectCollectionInterface
    {
        return $this->serviceCheckRepository->findAllAsCollection();
    }
}
