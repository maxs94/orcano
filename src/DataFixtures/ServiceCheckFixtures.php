<?php

namespace App\DataFixtures;

use App\Entity\ServiceCheck;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ServiceCheckFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $serviceCheck = (new ServiceCheck())
            ->setName('Fixture Service Check')
            ->setCheckScript($this->getReference(CheckScriptFixtures::REFERENCE_KEY_CHECK_SCRIPT))
            ->setCheckIntervalSeconds(15)
            ->setRetryIntervalSeconds(60)
            ->setMaxRetries(3)
            ->setNotificationsEnabled(false)
            ->addAssetGroup($this->getReference(AssetGroupFixtures::REFERENCE_KEY_ASSET_GROUP))
        ;
        $manager->persist($serviceCheck);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            AssetGroupFixtures::class,
            CheckScriptFixtures::class
        ];
    }
}
