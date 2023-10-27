<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Service\User;

use App\Repository\UserRepository;
use App\Service\User\RegistrationService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @covers \App\Service\User\RegistrationService
 *
 * @internal
 */
class RegistrationServiceTest extends TestCase
{
    public function testRegisterUser(): void
    {
        $hasher = $this->createMock(UserPasswordHasherInterface::class);
        $userRepo = $this->createMock(UserRepository::class);
        $em = $this->createMock(EntityManagerInterface::class);

        $service = new RegistrationService($hasher, $userRepo, $em);

        $result = $service->registerUser('test@localhost.local', 'test123', ['ROLE_USER']);

        $this->assertSame('User created.', $result);   
    }
}
