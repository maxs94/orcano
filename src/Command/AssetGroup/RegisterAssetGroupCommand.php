<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Command\AssetGroup;

use App\Entity\Asset;
use App\Entity\AssetGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'orcano:asset-group:register',
    description: 'registers an asset group'
)]
class RegisterAssetGroupCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'The name of the asset group')
            ->addOption('hostname', null, InputOption::VALUE_OPTIONAL, 'The hostname to add to the asset group (multiple hostnames separated by ,')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getOption('name');

        if ($name === null) {
            $io->error('The asset group name cannot be empty.');

            return Command::FAILURE;
        }

        $assetGroupRepo = $this->em->getRepository(AssetGroup::class);
        $assetRepo = $this->em->getRepository(Asset::class);

        $hostnameOption = $input->getOption('hostname');
        $hostnames = [];
        if ($hostnameOption !== null && stristr((string) $hostnameOption, ',')) {
            $hostnames = explode(',', (string) $hostnameOption);
        } elseif ($hostnameOption !== null) {
            $hostnames[] = $hostnameOption;
        }

        $assetGroup = $assetGroupRepo->findOneBy(['name' => $name]);

        if ($assetGroup !== null) {
            $io->text(sprintf('Updating asset group %s ...', $name));
        } else {
            $io->text(sprintf('Adding asset group %s ...', $name));
            $assetGroup = new AssetGroup();
        }

        $assetGroup->setName($name);

        foreach ($hostnames as $hostname) {
            $asset = $assetRepo->findOneBy(['hostname' => $hostname]);
            if ($asset === null) {
                $io->warning(sprintf('Asset with hostname %s not found.', $hostname));
            }
            $io->text(sprintf('Adding asset %s to asset group %s ...', $hostname, $name));
            $assetGroup->addAsset($asset);
        }

        $this->em->persist($assetGroup);
        $this->em->flush();

        $io->success(sprintf('Asset group %s successfully %s.', $name, $assetGroup->getId() === null ? 'added' : 'updated'));

        return Command::SUCCESS;
    }
}
