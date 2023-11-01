<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Event;

use App\DataObject\DataObjectInterface;
use Symfony\Contracts\EventDispatcher\Event;

class EntityUpsertEvent extends Event
{
    public const NAME = 'entity.upsert';

    public function __construct(protected readonly DataObjectInterface $entity) {}

    public function getEntity(): DataObjectInterface
    {
        return $this->entity;
    }
}
