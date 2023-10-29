<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Service\AssetGroup;

use App\DataObject\Collection\DataObjectCollection;
use App\Entity\AssetGroup;
use App\Repository\AssetGroupRepository;
use App\Service\AssetGroup\AssetGroupService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class AssetGroupServiceTest extends TestCase
{
    private AssetGroupService $service;

    /** @var MockObject&AssetGroupRepository */
    private AssetGroupRepository $repository;

    public function setUp(): void
    {
        $this->repository = $this->createMock(AssetGroupRepository::class);

        /** @var MockObject&EntityManagerInterface $em */
        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getRepository')->willReturn($this->repository);

        $this->service = new AssetGroupService($em);
    }

    public function testGetAssetGroupsByNames(): void
    {
        $this->repository->method('findByNamesAsCollection')->willReturn(new DataObjectCollection([
            (new AssetGroup())->setName('test1'),
            (new AssetGroup())->setName('test2'),
        ]));

        $assetGroups = $this->service->getAssetGroupsByNames(['test1', 'test2']);

        $this->assertCount(2, $assetGroups);
    }
}
