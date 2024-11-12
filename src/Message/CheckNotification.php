<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Message;

class CheckNotification
{
    public function __construct(
        private readonly int $assetId,
        private readonly int $serviceCheckId,
        private readonly string $hostname,
        private readonly ?string $ipv4Address,
        private readonly ?string $ipv6Address,
        private readonly string $checkScriptFilename
    ) {}

    public function getAssetId(): int
    {
        return $this->assetId;
    }

    public function getServiceCheckId(): int
    {
        return $this->serviceCheckId;
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
}
