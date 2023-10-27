<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Command\Asset;

use App\Entity\Asset;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'orcano:asset:list',
    description: 'lists assets'
)]
class ListAssetCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $assetRepo = $this->em->getRepository(Asset::class);
        $table = new Table($output);

        $table->setHeaders(['Name', 'Hostname', 'IPv4', 'IPv6', 'Asset groups']);

        $assets = $assetRepo->findAll();

        foreach ($assets as $asset) {
            $table->addRow([
                $asset->getName(),
                $asset->getHostname(),
                $asset->getIpv4Address(),
                $asset->getIpv6Address(),
                $asset->getAssetGroupsAsString(),
            ]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
