<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\Factory\TierListFactory;
use App\Service\Factory\UserFactory;
use App\Service\Password\PasswordGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create:user',
    description: 'Creates a new user',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserFactory $userFactory,
        private readonly TierListFactory $tierListFactory,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('username', InputArgument::REQUIRED, 'The username of the user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        if (!is_string($username)) {
            throw new \InvalidArgumentException('Username must be a string');
        }

        $output->writeln(sprintf('Creating %s user...', $username));

        if ($this->userRepository->usernameExist($username)) {
            $output->writeln('User already exist');

            return Command::FAILURE;
        }

        $otp = PasswordGenerator::generate();
        $user = $this->userFactory->create($username, $otp);
        $this->tierListFactory->create($user);

        $output->writeln('Creation completed');
        $output->writeln(sprintf('OTP: %s', $otp));

        return Command::SUCCESS;
    }
}
