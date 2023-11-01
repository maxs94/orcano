<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\UserSetting;
use App\Tests\Trait\GetterSetterTestingTrait;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \App\Entity\User
 */
class UserTest extends TestCase
{
    use GetterSetterTestingTrait;

    public function testGetRoles(): void
    {
        $user = new User();
        $this->assertSame(['ROLE_USER'], $user->getRoles());
    }

    public function testEntity(): void
    {
        $user = new User();
        $user->setId(1);
        $user->setEmail('test@localhost');

        $userSetting = new UserSetting();
        $userSetting->setId(1);

        $this->assertSame('test@localhost', $user->getUserIdentifier());

        $user->addUserSetting($userSetting);

        $this->assertCount(1, $user->getUserSettings());

        $user->removeUserSetting($userSetting);

        $this->assertCount(0, $user->getUserSettings());
    }
}
