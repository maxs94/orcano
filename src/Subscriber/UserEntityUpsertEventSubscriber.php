<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Subscriber;

use App\Context\ContextLoader;
use App\Event\EntityUpsertEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserEntityUpsertEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ContextLoader $contextLoader
    ) {}

    public static function getSubscribedEvents()
    {
        return [
            EntityUpsertEvent::class => 'onEntityUpsert',
        ];
    }

    public function onEntityUpsert(EntityUpsertEvent $event): void
    {
        $entity = $event->getEntity();

        if ($entity instanceof \App\Entity\User) {
            $this->contextLoader->update();
        }
    }
}
