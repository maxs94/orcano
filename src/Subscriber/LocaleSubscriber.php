<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Subscriber;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Translation\LocaleSwitcher;

class LocaleSubscriber implements EventSubscriberInterface
{
    public const FALLBACK_LOCALE = 'en';

    public function __construct(private readonly LocaleSwitcher $localeSwitcher) {}

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $user = $request->getSession()->get('currentUser') ?? null;
        $userLocale = ($user instanceof User) ? $user->getLanguage() : 'auto';
        $requestLocale = $request->attributes->get('_locale') ?? null;
        $sessionLocale = $request->getSession()->get('_locale') ?? null;
        $browserLocale = $this->getBrowserLocale();

        // set locale from user, then route parameter, then session, then browser
        if ($userLocale !== 'auto') {
            $locale = $userLocale;
        } elseif ($requestLocale !== null) {
            $locale = $requestLocale;
        } elseif ($sessionLocale !== null) {
            $locale = $sessionLocale;
        } elseif ($browserLocale !== null) {
            $locale = $browserLocale;
        } else {
            $locale = self::FALLBACK_LOCALE;
        }

        $this->localeSwitcher->setLocale($locale);
    }

    private function getBrowserLocale(): ?string
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        }

        return null;
    }
}
