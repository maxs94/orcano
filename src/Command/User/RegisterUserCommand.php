<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Command\User;

use App\Service\User\RegistrationService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'orcano:user:register',
    description: 'Registers a new user',
)]
class RegisterUserCommand extends Command
{
    public function __construct(
        private readonly RegistrationService $registrationService
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'The email of the user.');
        $this->addArgument('password', InputArgument::REQUIRED, 'The password of the user.');
        $this->addArgument('roles', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The roles of the user, space separated. (ROLE_USER, ROLE_ADMIN)');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $roles = $input->getArgument('roles');

        if (empty($email)) {
            $io->error('The email cannot be empty.');

            return Command::FAILURE;
        }

        if (empty($password)) {
            $io->error('The password cannot be empty.');

            return Command::FAILURE;
        }

        if (empty($roles)) {
            $io->error('The roles cannot be empty. Please provide ROLE_USER and/or ROLE_ADMIN. Space separated.');

            return Command::FAILURE;
        }

        $result = $this->registrationService->registerUser($email, $password, $roles);

        $io->success($result);

        return Command::SUCCESS;
    }
}
