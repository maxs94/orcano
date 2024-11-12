<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Subscriber;

use App\Context\ContextLoader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ContextLoader $contextLoader
    ) {}

    public static function getSubscribedEvents()
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(): void
    {
        $this->contextLoader->update();
    }
}
