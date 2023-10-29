<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Listener;

use App\Entity\User;
use App\Listener\LoginSuccessEventListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

/**
 * @internal
 *
 * @covers \App\Listener\LoginSuccessEventListener
 */
class LoginSuccessEventListenerTest extends TestCase
{
    public function testPasswordGetsRemovedAfterLogin(): void
    {
        $user = new User();
        $user->setId(1);
        $user->setPassword('testpassword');

        $session = new Session(new MockFileSessionStorage());

        /** @var MockObject&Request $request */
        $request = $this->createMock(Request::class);
        $request->method('getSession')->willReturn($session);

        $listener = new LoginSuccessEventListener();

        /** @var MockObject&LoginSuccessEvent $event */
        $event = $this->createMock(LoginSuccessEvent::class);
        $event->method('getRequest')->willReturn($request);
        $event->method('getUser')->willReturn($user);

        $listener->__invoke($event);

        $currentUserFromSession = $session->get('currentUser');

        $this->assertSame(1, $currentUserFromSession->getId());
        $this->assertEmpty($currentUserFromSession->getPassword());
    }
}
