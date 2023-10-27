<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Command\ServiceCheck;

use App\Entity\AssetGroup;
use App\Entity\CheckScript;
use App\Entity\ServiceCheck;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'orcano:service-check:register',
    description: 'register a service check'
)]
class RegisterServiceCheckCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'The name of the service check')
            ->addOption('check-script', null, InputOption::VALUE_REQUIRED, 'The check script to use (name of orcano:check-script:list)')
            ->addOption('asset-groups', null, InputOption::VALUE_REQUIRED, 'The asset groups to use (name of orcano:asset-group:list), comma separated list')
            ->addOption('interval', null, InputOption::VALUE_OPTIONAL, 'The interval in seconds to run the check script', ServiceCheck::DEFAULT_CHECK_INTERVAL)
            ->addOption('retry-interval', null, InputOption::VALUE_OPTIONAL, 'The interval in seconds to retry the check script', ServiceCheck::DEFAULT_CHECK_INTERVAL)
            ->addOption('notifications-enabled', null, InputOption::VALUE_OPTIONAL, 'Whether notifications are enabled', true)
            ->addOption('enabled', null, InputOption::VALUE_OPTIONAL, 'Whether the service check is enabled', true)
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getOption('name');
        $availableCheckScripts = $this->getAvailableCheckScripts();
        $availableAssetGroups = $this->getAvailableAssetGroups();

        if ($name === null) {
            $io->error('The service check name cannot be empty.');

            return Command::FAILURE;
        }

        $serviceCheckRepo = $this->em->getRepository(ServiceCheck::class);
        $serviceCheck = $serviceCheckRepo->findOneBy(['name' => $name]);
        if ($serviceCheck !== null) {
            $io->warning(sprintf('Updating service check "%s".', $name));
        } else {
            $io->info(sprintf('Creating service check "%s".', $name));
            $serviceCheck = new ServiceCheck();
        }

        $checkScriptOption = $input->getOption('check-script');
        $checkScript = $availableCheckScripts[$checkScriptOption] ?? null;

        if ($checkScript === null) {
            $io->error('The check script cannot be empty or has not been found.');
            $io->listing(array_keys($availableCheckScripts));

            return Command::FAILURE;
        }

        $assetGroupOption = $input->getOption('asset-groups');
        $assetGroups = $this->getAssetGroupsFromOption($assetGroupOption, $availableAssetGroups);
        if (empty($assetGroups)) {
            $io->error('The asset groups cannot be empty or the given groups have not been found.');
            $io->listing(array_keys($availableAssetGroups));

            return Command::FAILURE;
        }

        $checkInterval = intval($input->getOption('interval') ?? ServiceCheck::DEFAULT_CHECK_INTERVAL);
        $retryInterval = intval($input->getOption('retry-interval') ?? ServiceCheck::DEFAULT_CHECK_INTERVAL);
        $notificatiosEnabled = boolval($input->getOption('notifications-enabled'));
        $enabled = boolval($input->getOption('enabled'));

        $serviceCheck->setName($name);
        $serviceCheck->setCheckScript($checkScript);
        foreach ($assetGroups as $assetGroup) {
            $serviceCheck->addAssetGroup($assetGroup);
        }
        $serviceCheck->setCheckIntervalSeconds($checkInterval);
        $serviceCheck->setRetryIntervalSeconds($retryInterval);
        $serviceCheck->setNotificationsEnabled($notificatiosEnabled);
        $serviceCheck->setEnabled($enabled);

        $this->em->persist($serviceCheck);
        $this->em->flush();

        return Command::SUCCESS;
    }

    /** @return array<CheckScript> */
    private function getAvailableCheckScripts(): array
    {
        $checkScriptsRepo = $this->em->getRepository(CheckScript::class);
        $checkScripts = $checkScriptsRepo->findAll();

        $scripts = [];

        foreach ($checkScripts as $checkScript) {
            $scripts[$checkScript->getName()] = $checkScript;
        }

        return $scripts;
    }

    /** @return array<AssetGroup> */
    private function getAvailableAssetGroups(): array
    {
        $assetGroupRepo = $this->em->getRepository(AssetGroup::class);
        $assetGroups = $assetGroupRepo->findAll();

        $groups = [];

        foreach ($assetGroups as $assetGroup) {
            $groups[$assetGroup->getName()] = $assetGroup;
        }

        return $groups;
    }

    /**
     * @param array<AssetGroup> $availableAssetGroups
     *
     * @return array<AssetGroup>
     */
    private function getAssetGroupsFromOption(string $assetGroupsString, array $availableAssetGroups): array
    {
        $assetGroupOptions = explode(',', $assetGroupsString);

        $assetGroups = [];

        foreach ($assetGroupOptions as $assetGroupOption) {
            if (isset($availableAssetGroups[$assetGroupOption])) {
                $assetGroups[] = $availableAssetGroups[$assetGroupOption];
            }
        }

        return $assetGroups;
    }
}
