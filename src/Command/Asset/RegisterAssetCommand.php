<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Command\Asset;

use App\Entity\Asset;
use App\Entity\AssetGroup;
use App\Service\AssetGroup\AssetGroupService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'orcano:asset:register',
    description: 'registers an asset'
)]
class RegisterAssetCommand extends Command
{
    public function __construct(
        private readonly AssetGroupService $assetGroupService,
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addOption('hostname', null, InputOption::VALUE_REQUIRED, 'The hostname of the asset')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'The alias name of the asset')
            ->addOption('ipv4', null, InputOption::VALUE_OPTIONAL, 'The ipv4 address of the asset')
            ->addOption('ipv6', null, InputOption::VALUE_OPTIONAL, 'The ipv6 address of the asset')
            ->addOption('groups', null, InputOption::VALUE_OPTIONAL, 'The groups of the asset (comma separated)')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('hostname') === null) {
            $io->error('The hostname cannot be empty.');

            return Command::FAILURE;
        }

        $assetRepo = $this->em->getRepository(Asset::class);

        $hostname = $input->getOption('hostname');

        $asset = $assetRepo->findOneBy(['hostname' => $hostname]);

        if ($asset !== null) {
            $io->text(sprintf('Updating hostname %s ...', $hostname));
        } else {
            $io->text(sprintf('Adding hostname %s ...', $hostname));
            $asset = new Asset();
        }

        $asset->setName($input->getOption('name'));
        $asset->setHostname($hostname);
        $asset->setIpv4Address($input->getOption('ipv4'));
        $asset->setIpv6Address($input->getOption('ipv6'));

        $groups = $input->getOption('groups');
        if ($groups !== null) {
            $assetGroups = $this->assetGroupService->getAssetGroupsByNames(explode(',', (string) $groups));

            /** @var AssetGroup $assetGroup */
            foreach ($assetGroups as $assetGroup) {
                $io->text(sprintf('Adding asset %s to group %s ...', $asset->getHostname(), $assetGroup->getName()));
                $asset->addAssetGroup($assetGroup);
            }
        }

        $this->em->persist($asset);
        $this->em->flush();

        $io->success(sprintf('Hostname %s successfully %s.', $hostname, $asset->getId() !== null ? 'updated' : 'added'));

        return Command::SUCCESS;
    }
}
