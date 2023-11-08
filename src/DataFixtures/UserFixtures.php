<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $manager->flush();
    }

    private function getUserData(): array
    {

        $users = [

        ];

        return $users;

    }
}