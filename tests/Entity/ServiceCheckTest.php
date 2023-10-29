<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Entity;

use App\Entity\AssetGroup;
use App\Entity\ServiceCheck;
use App\Tests\Trait\GetterSetterTestingTrait;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \App\Entity\ServiceCheck
 */
class ServiceCheckTest extends TestCase
{
    use GetterSetterTestingTrait;

    public function testEntity(): void
    {
        $serviceCheck = new ServiceCheck();
        $serviceCheck->setId(1);

        $serviceCheck->setNotificationsEnabled(true);
        $serviceCheck->setEnabled(true);

        $this->assertTrue($serviceCheck->isNotificationsEnabled());
        $this->assertTrue($serviceCheck->isEnabled());

        $assetGroup1 = new AssetGroup();
        $assetGroup1->setName('assetGroup1');

        $assetGroup2 = new AssetGroup();
        $assetGroup2->setName('assetGroup2');

        $serviceCheck->addAssetGroup($assetGroup1);
        $serviceCheck->addAssetGroup($assetGroup2);

        $this->assertSame('assetGroup1, assetGroup2', $serviceCheck->getAssetGroupsAsString());
    }
}
