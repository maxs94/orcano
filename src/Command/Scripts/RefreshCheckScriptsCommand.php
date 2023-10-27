<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Command\Scripts;

use App\Service\Scripts\ScriptsService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'orcano:scripts:refresh',
    description: 'read check scripts and update database',
)]
class RefreshCheckScriptsCommand extends Command
{
    public function __construct(
        private readonly ScriptsService $scriptsService
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->scriptsService->refreshScripts();

        if ($result === false) {
            $io->error('Could not refresh scripts.');

            return Command::FAILURE;
        }

        $io->success('Scripts refreshed.');

        return Command::SUCCESS;
    }
}
