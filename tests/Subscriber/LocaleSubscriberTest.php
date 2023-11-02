<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Subscriber;

use App\Entity\User;
use App\Subscriber\LocaleSubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Translation\LocaleSwitcher;

/**
 * @internal
 *
 * @covers \App\Subscriber\LocaleSubscriber
 */
class LocaleSubscriberTest extends TestCase
{
    private LocaleSubscriber $subscriber;

    /** @var MockObject&LocaleSwitcher */
    private LocaleSwitcher $localeSwitcher;

    /** @var MockObject&Request */
    private Request $request;

    /** @var MockObject&RequestEvent */
    private RequestEvent $event;

    private User $user;

    private Session $session;

    public function setUp(): void
    {
        $this->localeSwitcher = $this->createMock(LocaleSwitcher::class);
        $this->subscriber = new LocaleSubscriber($this->localeSwitcher);

        $this->request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->request->attributes = new ParameterBag();
        $this->request->headers = new ParameterBag();

        $this->event = $this->getMockBuilder(RequestEvent::class)->disableOriginalConstructor()->getMock();
        $this->event->method('getRequest')->willReturn($this->request);

        $this->user = new User();

        $this->session = new Session(new MockFileSessionStorage());
        $this->session->set('currentUser', $this->user);

        $this->request->method('getSession')->willReturn($this->session);
    }

    public function testSubscribedEvents(): void
    {
        $this->assertArrayHasKey(RequestEvent::class, LocaleSubscriber::getSubscribedEvents());
    }

    public function testLocaleFromUserSetting(): void
    {
        $this->user->setLanguage('xx');

        $this->localeSwitcher->expects($this->once())
            ->method('setLocale')
            ->with('xx')
        ;

        $this->subscriber->onKernelRequest($this->event);
    }

    public function testLocaleFromRequest(): void
    {
        $this->user->setLanguage('auto');
        $this->request->attributes->set('_locale', 'xx');

        $this->localeSwitcher->expects($this->once())
            ->method('setLocale')
            ->with('xx')
        ;

        $this->subscriber->onKernelRequest($this->event);
    }

    public function testLocaleFromSession(): void
    {
        $this->user->setLanguage('auto');

        $this->session->set('_locale', 'xx');

        $this->localeSwitcher->expects($this->once())
            ->method('setLocale')
            ->with('xx')
        ;

        $this->subscriber->onKernelRequest($this->event);
    }

    public function testLocaleFromBrowser(): void
    {
        $this->user->setLanguage('auto');

        $this->request->headers->set('Accept-Language', 'de-DE,de');

        $this->localeSwitcher->expects($this->once())
            ->method('setLocale')
            ->with('de')
        ;

        $this->subscriber->onKernelRequest($this->event);
    }

    public function testLocaleFallback(): void
    {
        $this->user->setLanguage('auto');

        $this->localeSwitcher->expects($this->once())
            ->method('setLocale')
            ->with(LocaleSubscriber::FALLBACK_LOCALE)
        ;

        $this->subscriber->onKernelRequest($this->event);
    }

    public function testBrokenBrowserLocaleShouldReturnFallback(): void
    {
        $this->user->setLanguage('auto');

        $this->request->headers->set('Accept-Language', 'garbageLocale');

        $this->localeSwitcher->expects($this->once())
            ->method('setLocale')
            ->with(LocaleSubscriber::FALLBACK_LOCALE)
        ;

        $this->subscriber->onKernelRequest($this->event);
    }
}
