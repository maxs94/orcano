<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Entity;

use App\Entity\User;
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
}
