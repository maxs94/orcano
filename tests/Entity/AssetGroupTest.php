<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Entity;

use App\Entity\Asset;
use App\Entity\AssetGroup;
use App\Entity\ServiceCheck;
use App\Tests\Trait\GetterSetterTestingTrait;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \App\Entity\Asset
 * @covers \App\Entity\AssetGroup
 * @covers \App\Entity\ServiceCheck
 */
class AssetGroupTest extends TestCase
{
    use GetterSetterTestingTrait;

    public function testEntity(): void
    {
        $assetGroup = new AssetGroup();

        $asset = new Asset();
        $asset->setId(1);
        $asset->setName('asset1');

        $serviceCheck = new ServiceCheck();
        $serviceCheck->setId(1);
        $serviceCheck->addAssetGroup($assetGroup);

        $assetGroup->setId(1);
        $assetGroup->addAsset($asset);
        $assetGroup->addServiceCheck($serviceCheck);

        $this->assertCount(1, $assetGroup->getAssets());
        $this->assertCount(1, $assetGroup->getServiceChecks());

        $assetGroup->removeAsset($asset);
        $assetGroup->removeServiceCheck($serviceCheck);

        $this->assertSame(0, $assetGroup->getAssetCount());
    }
}
