<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Command\Scheduler;

use App\Service\SchedulerService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'orcano:scheduler:run',
    description: 'schedules checks and sends them to the workers'
)]
class RunSchedulerCommand extends Command
{
    public function __construct(
        private readonly SchedulerService $schedulerService
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->schedulerService->run();

        return Command::SUCCESS;
    }
}
