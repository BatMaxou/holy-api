<?php

namespace App\Command;

use App\Service\Factory\TierListFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:initialize:tier-list',
    description: 'Initialize First Tier List',
)]
class InitializeTierList extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // $output->writeln('Initializing Tier List...');
        // $this->tierListFactory->create();
        // $output->writeln('Tier List initialized');

        // TODO: Remove

        return Command::SUCCESS;
    }
}
