<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Command\Scripts;

use App\Entity\CheckScript;
use App\Service\Scripts\ScriptsService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'orcano:scripts:list',
    description: 'lists all registered check scripts',
)]
class ListCheckScriptsCommand extends Command
{
    public function __construct(
        private readonly ScriptsService $scriptsService
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $scripts = $this->scriptsService->getAllScripts();

        $table = new Table($output);
        $table->setHeaders(['Name', 'Description', 'Filename', 'Registered', 'Changed']);

        /** @var CheckScript $script */
        foreach ($scripts as $script) {
            $table->addRow([
                $script->getName(),
                $script->getDescription(),
                $script->getFilename(),
                $script->getIsRegistered() ? 'yes' : 'no',
                $script->getIsChangedInFilesystem() ? 'yes' : 'no',
            ]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
