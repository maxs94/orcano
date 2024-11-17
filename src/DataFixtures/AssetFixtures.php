<?php
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataFixtures;

use App\Entity\Asset;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AssetFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $asset = new Asset();
        $asset->setName('Fixture');
        $asset->setHostname('www.google.com');
        $asset->setIpv4Address('172.217.18.4');
        $asset->setIpv6Address('2a00:1450:4016:80a::2004');
        $asset->addAssetGroup($this->getReference(AssetGroupFixtures::REFERENCE_KEY_ASSET_GROUP));

        $manager->persist($asset);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            AssetGroupFixtures::class,
        ];
    }
}
