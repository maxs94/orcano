<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em
    ) {}

    /**
     * @param array<string> $roles
     */
    public function registerUser(string $email, string $password, array $roles): string
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if ($user === null) {
            $user = new User();
            $user->setEmail($email);
            $returnMessage = 'User created.';
        } else {
            $returnMessage = 'User updated.';
        }

        $user->setRoles($roles);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->em->persist($user);
        $this->em->flush();

        return $returnMessage;
    }
}
