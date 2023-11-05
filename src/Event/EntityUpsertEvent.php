<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Event;

use App\Entity\ApiEntityInterface;
use Symfony\Contracts\EventDispatcher\Event;

class EntityUpsertEvent extends Event
{
    public const NAME = 'entity.upsert';

    public function __construct(protected readonly ApiEntityInterface $entity) {}

    public function getEntity(): ApiEntityInterface
    {
        return $this->entity;
    }
}
