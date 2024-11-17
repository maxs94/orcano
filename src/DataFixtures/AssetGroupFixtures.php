<?php
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataFixtures;

use App\Entity\AssetGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AssetGroupFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_KEY_ASSET_GROUP = 'ref-assetGroup-Fixture';

    public function load(ObjectManager $manager): void
    {
        $assetGroup = new AssetGroup();
        $assetGroup->setName('Fixture');

        $this->addReference(self::REFERENCE_KEY_ASSET_GROUP, $assetGroup);
        $manager->persist($assetGroup);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
