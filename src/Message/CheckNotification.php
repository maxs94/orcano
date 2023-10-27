<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Message;

use App\Condition\ConditionCollection;
use Symfony\Component\Uid\Uuid;

class CheckNotification
{
    private string $id;

    public function __construct(
        private readonly string $hostname,
        private readonly ?string $ipv4Address,
        private readonly ?string $ipv6Address,
        private readonly string $checkScriptFilename,
        private readonly ConditionCollection $conditions,
    ) {
        $this->id = Uuid::v4()->toRfc4122();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function getIpv4Address(): ?string
    {
        return $this->ipv4Address;
    }

    public function getIpv6Address(): ?string
    {
        return $this->ipv6Address;
    }

    public function getCheckScriptFilename(): string
    {
        return $this->checkScriptFilename;
    }

    public function getConditions(): ConditionCollection
    {
        return $this->conditions;
    }
}
