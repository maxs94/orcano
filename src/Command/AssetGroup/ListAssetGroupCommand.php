<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Command\AssetGroup;

use App\Entity\AssetGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'orcano:asset-group:list',
    description: 'lists assets groups'
)]
class ListAssetGroupCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $assetGroupRepo = $this->em->getRepository(AssetGroup::class);
        $table = new Table($output);

        $table->setHeaders(['Name', 'Assets assigned']);

        $assetGroups = $assetGroupRepo->findAll();

        foreach ($assetGroups as $assetGroup) {
            $table->addRow([
                $assetGroup->getName(),
                $assetGroup->getAssetCount(),
            ]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
