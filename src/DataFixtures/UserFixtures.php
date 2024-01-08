<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {}

    public function load(ObjectManager $manager): void
    {
        
        $users = $this->getUserData();

        foreach($users as $key => $data){

            ${$key} = new User();
            ${$key}->setEmail($data['email']);
            ${$key}->setRoles($data['roles']);

            $password = $this->hasher->hashPassword(${$key}, $data['password']);

            ${$key}->setPassword($password);
            ${$key}->setName($data['name']);
            ${$key}->setTheme($data['theme']);
            ${$key}->setRowLimit($data['rowLimit']);
            ${$key}->setLanguage($data['language']);
            $manager->persist(${$key});

        }

        $manager->flush();
    }

    private function getUserData(): array
    {

        $users = [
            'standard-user' => [
                'email' => 'test@localhost.local',
                'roles' => ['ROLE_USER'],
                'password' => 'test',
                'name' => 'User',
                'theme' => 'dark',
                'rowLimit' => 25,
                'language' => 'auto'
            ],
            'admin-user' => [
                'email' => 'admin@localhost.local',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'admin',
                'name' => 'Admin',
                'theme' => 'light',
                'rowLimit' => 25,
                'language' => 'auto'
            ]
        ];

        return $users;

    }
}