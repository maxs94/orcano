<?php

namespace App\Command\Preview;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'orcano:preview',
    description: 'Sets up Data to preview Orcano',
)]
class OrcanoPreviewCommand extends Command
{
    public function __construct(private readonly ParameterBagInterface $parameterBag)
    {
        parent::__construct();
    }

    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        
        $io = new SymfonyStyle($input, $output);
        
        $fixturesPath = $this->parameterBag->get('kernel.project_dir') . '/src/DataFixtures';

        $fixtures = Finder::create()->in($fixturesPath);

        $progressBar = new ProgressBar($output, count($fixtures));
        $progressBar->start();

        foreach($fixtures as $fixture){

            $class = str_replace('Fixtures.php', '', $fixture->getRelativePathname());
            
            $io->info($class . ' successfully created');
            $progressBar->advance();

        }

        $progressBar->finish();
        $output->writeln(PHP_EOL);
        

        $process = new Process(['php', 'bin/console', 'doctrine:fixtures:load', '--no-interaction']);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        
        $output->writeln($process->getOutput());

        $io->success('Preview Data was successfully loaded!');

        return Command::SUCCESS;
    }
}
