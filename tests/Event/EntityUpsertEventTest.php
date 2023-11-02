<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Event;

use App\Entity\User;
use App\Event\EntityUpsertEvent;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \App\Event\EntityUpsertEvent
 */
class EntityUpsertEventTest extends TestCase
{
    public function testEvent(): void
    {
        $event = new EntityUpsertEvent(
            $this->createMock(User::class)
        );
        $this->assertInstanceOf(User::class, $event->getEntity());
    }
}
