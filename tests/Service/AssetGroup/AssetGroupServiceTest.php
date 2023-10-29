<?PHP 
declare(strict_types=1);

namespace App\Tests\Service\AssetGroup;

use App\DataObject\Collection\DataObjectCollection;
use App\Entity\AssetGroup;
use App\Repository\AssetGroupRepository;
use App\Service\AssetGroup\AssetGroupService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AssetGroupServiceTest extends TestCase
{
    private AssetGroupService $service;

    /** @var MockObject&AssetGroupRepository */
    private AssetGroupRepository $repository;

    public function setUp(): void {
    
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
