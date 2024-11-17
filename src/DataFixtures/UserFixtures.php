<?php
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $users = $this->getUserData();

        foreach ($users as $key => $data) {
            ${$key} = new User();
            ${$key}->setEmail($data['email']);
            ${$key}->setRoles($data['roles']);

            $password = $this->hasher->hashPassword(${$key}, $data['password']);

            ${$key}->setPassword($password);
            ${$key}->setName($data['name']);
            ${$key}->setTheme($data['theme']);
            ${$key}->setRowLimit($data['rowLimit']);
            ${$key}->setLanguage($data['language']);

            $this->addReference($key, ${$key});

            $manager->persist(${$key});
        }

        $manager->flush();
    }

    /**
     * @return array<string, array<string, array<int, string>|int|string>>
     */
    private function getUserData(): array
    {
        return [
            'standard-user' => [
                'email' => 'test@localhost.local',
                'roles' => ['ROLE_USER'],
                'password' => 'test',
                'name' => 'User',
                'theme' => 'dark',
                'rowLimit' => 25,
                'language' => 'auto',
            ],
            'admin-user' => [
                'email' => 'admin@localhost.local',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'admin',
                'name' => 'Admin',
                'theme' => 'light',
                'rowLimit' => 25,
                'language' => 'auto',
            ],
        ];
    }
}
