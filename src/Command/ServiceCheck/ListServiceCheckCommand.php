<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Command\ServiceCheck;

use App\Entity\ServiceCheck;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'orcano:service-check:list',
    description: 'lists all registered service checks'
)]
class ListServiceCheckCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $serviceChecksRepo = $this->em->getRepository(ServiceCheck::class);
        $serviceChecks = $serviceChecksRepo->findAll();

        $table = new Table($output);
        $table->setHeaders(['name', 'check script', 'asset groups', 'interval', 'retry', 'max retries', 'notifications', 'enabled']);

        foreach ($serviceChecks as $serviceCheck) {
            $table->addRow([
                $serviceCheck->getName(),
                $serviceCheck->getCheckScript()->getName(),
                $serviceCheck->getAssetGroupsAsString(),
                $serviceCheck->getCheckIntervalSeconds(),
                $serviceCheck->getRetryIntervalSeconds(),
                $serviceCheck->getMaxRetries(),
                $serviceCheck->isNotificationsEnabled() ? 'yes' : 'no',
                $serviceCheck->isEnabled() ? 'yes' : 'no',
            ]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
