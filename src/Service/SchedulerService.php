<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service;

use App\Entity\Asset;
use App\Entity\AssetGroup;
use App\Entity\ServiceCheck;
use App\Entity\ServiceCheckWorkerStats;
use App\Message\CheckNotification;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SchedulerService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MessageBusInterface $bus,
        private readonly LoggerInterface $logger
    ) {}

    public function run(): void
    {
        /* @var AssetGroupRepository $assetGroupRepo */
        $assetGroupRepo = $this->em->getRepository(AssetGroup::class);

        $this->em->getRepository(Asset::class);

        $assetGroups = $assetGroupRepo->findAll();

        /** @var AssetGroup $assetGroup */
        foreach ($assetGroups as $assetGroup) {
            $checks = $assetGroup->getServiceChecks();

            $assets = $assetGroup->getAssets();

            /** @var ServiceCheck $check */
            foreach ($checks as $check) {
                /** @var Asset $asset */
                foreach ($assets as $asset) {
                    if ($this->isCheckScheduled($asset, $check)) {
                        $this->runCheck($asset, $check);
                    }
                }
            }
        }
    }

    private function runCheck(Asset $asset, ServiceCheck $check): void
    {
        $checkScript = $check->getCheckScript();

        $message = new CheckNotification(
            $asset->getId(),
            $asset->getHostname(),
            $asset->getIpv4Address(),
            $asset->getIpv6Address(),
            $checkScript->getFilename()
        );

        $this->logger->info(sprintf('Scheduling check %s on %s (%d) using script %s',
            $check->getName(),
            $asset->getHostname(),
            $message->getAssetId(),
            $checkScript->getFilename())
        );

        $this->bus->dispatch($message);
    }

    private function isCheckScheduled(Asset $asset, ServiceCheck $check): bool
    {
        $serviceCheckWorkerStatsRepo = $this->em->getRepository(ServiceCheckWorkerStats::class);
        $serviceCheckWorkerStats = $serviceCheckWorkerStatsRepo->findOneBy([
            'asset' => $asset,
            'serviceCheck' => $check,
        ]);

        if ($serviceCheckWorkerStats === null) {
            return true;
        }

        dd('TODO: check if check is scheduled');
    }
}
