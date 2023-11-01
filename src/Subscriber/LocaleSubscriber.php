<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class LocaleSubscriber implements EventSubscriberInterface
{
    public const FALLBACK_LOCALE = 'en';

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $sessionLocale = $request->getSession()->get('_locale') ?? null;
        $browserLocale = $this->getBrowserLocale();

        // set locale from route parameter, then session, then browser
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } elseif ($sessionLocale !== null) {
            $request->setLocale($request->getSession()->get('_locale', 'en'));
        } elseif ($browserLocale !== null) {
            $request->setLocale($browserLocale);
        } else {
            $request->setLocale(self::FALLBACK_LOCALE);
        }
    }

    private function getBrowserLocale(): ?string
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        }

        return null;
    }
}
