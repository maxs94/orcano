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
use App\Repository\AssetGroupRepository;
use App\Repository\AssetRepository;
use App\Repository\ServiceCheckWorkerStatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SchedulerService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MessageBusInterface $bus,
        private readonly AssetGroupRepository $assetGroupRepository,
        private readonly AssetRepository $assetRepository,
        private readonly ServiceCheckWorkerStatsRepository $serviceCheckWorkerStatsRepository,
        private readonly LoggerInterface $logger
    ) {}

    public function run(): void
    {
        $this->runAssetChecks();
        //$this->runAssetGroupChecks();
    }

    private function runAssetChecks(): void 
    {
        $assets = $this->assetRepository->findAll();

        /** @var Asset $asset */
        foreach ($assets as $asset) {
            $checks = $asset->getServiceChecks();

            /** @var ServiceCheck $check */
            foreach ($checks as $check) {
                if ($this->isCheckScheduled($asset, $check)) {
                    $this->runCheck($asset, $check);
                } else {
                    $this->logger->info(sprintf('Check %s is not scheduled for asset %s', $check->getId(), $asset->getId()));
                }
            }
        }
    }

    private function runAssetGroupChecks(): void 
    {
        $assetGroups = $this->assetGroupRepository->findAll();

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
        $this->logger->info(sprintf('Running check %s on %s (%d)', $check->getName(), $asset->getHostname(), $asset->getId()));

        $checkScript = $check->getCheckScript();

        $message = new CheckNotification(
            $asset->getId(),
            $check->getId(),
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
        $serviceCheckWorkerStats = $this->serviceCheckWorkerStatsRepository->findOneBy([
            'asset' => $asset,
            'serviceCheck' => $check,
        ]);

        $this->logger->warning('todo: check if check is actually scheduled');

        if ($serviceCheckWorkerStats === null) {
            return true;
        }

        return false;
    }
}
