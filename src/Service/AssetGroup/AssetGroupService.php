<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\AssetGroup;

use App\DataObject\Collection\DataObjectCollectionInterface;
use App\Entity\AssetGroup;
use App\Repository\AssetGroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class AssetGroupService
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {}

    /** @param array<string> $assetGroupNames */
    public function getAssetGroupsByNames(array $assetGroupNames): DataObjectCollectionInterface
    {
        /** @var AssetGroupRepository $repo */
        $repo = $this->em->getRepository(AssetGroup::class);

        return $repo->findByNamesAsCollection($assetGroupNames);
    }
}
