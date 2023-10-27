<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Command;

use App\Condition\EqualsCondition;
use App\DataObject\ScriptResultDataObject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'orcano:condition:test',
    description: 'debug, create a condition and output as serialized string'
)]
class ConditionTestCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $conditions = [
            ScriptResultDataObject::RESULT_OK => [
                'result' => new EqualsCondition(0),
            ],
        ];

        echo serialize($conditions);
        echo PHP_EOL;

        return Command::SUCCESS;
    }
}
