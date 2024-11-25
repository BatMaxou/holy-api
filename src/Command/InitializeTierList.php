<?php

namespace App\Command;

use App\Service\Builder\TierListBuilder;
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
    public function __construct(
        private readonly TierListBuilder $tierListBuilder,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Initializing Tier List...');
        $this->tierListBuilder->build();
        $output->writeln('Tier List initialized');

        return Command::SUCCESS;
    }
}
