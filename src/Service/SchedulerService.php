<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service;

use App\Condition\ConditionCollection;
use App\Condition\EqualsCondition;
use App\Condition\MinMaxCondition;
use App\Entity\Asset;
use App\Entity\AssetGroup;
use App\Entity\ServiceCheck;
use App\Entity\ServiceCheckWorkerStats;
use App\Message\CheckNotification;
use App\Repository\AssetGroupRepository;
use App\Repository\AssetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SchedulerService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MessageBusInterface $bus,
        private readonly LoggerInterface $logger
    ) {
    }

    public function run(): void
    {
        /*@var AssetGroupRepository $assetGroupRepo  */
        $assetGroupRepo = $this->em->getRepository(AssetGroup::class);

        /** @var AssetRepository $assetRepo */
        $assetRepo = $this->em->getRepository(Asset::class);

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

        // todo:
        //
        // have specific conditions per checkscript
        //
        // have an inheritance would make very much sense:
        // ServiceCheck -> AssetGroup -> Asset
        // $conditionTemplateString = // serialized conditions string
        // $conditions = unserialize($conditionTemplateString);
        $conditions = new ConditionCollection();
        $conditions->addCondition('result', new EqualsCondition(0));
        $conditions->addCondition('time', new MinMaxCondition(0, 1000, 20));  // ok if between 0 and 1000, warn if between 20 and 1000

        $message = new CheckNotification(
            $asset->getHostname(),
            $asset->getIpv4Address(),
            $asset->getIpv6Address(),
            $checkScript->getFilename(),
            $conditions
        );

        $this->logger->info(sprintf('%s - Scheduling check %s on %s using script %s', $message->getId(), $check->getName(), $asset->getHostname(), $checkScript->getFilename()));

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
