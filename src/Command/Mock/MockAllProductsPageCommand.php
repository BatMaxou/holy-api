<?php

namespace App\Command\Mock;

use App\Service\Crawling\ProductCrawler;
use App\Service\Scraping\Scraper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'mock:scrap:products',
    description: 'Mock html content of "all products" page',
)]
class MockAllProductsPageCommand extends Command
{
    public function __construct(
        private readonly Scraper $scraper,
        private readonly ProductCrawler $productCrawler,
        private readonly string $baseUrl,
        private readonly string $allProductsPath,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->productCrawler->setCurrentOutput($output);
        $output->writeln('Mocking "all products" page:');

        $html = $this->scraper->scrap(sprintf('%s.%s', $this->baseUrl, $this->allProductsPath));
        $this->productCrawler->writeMockedContent($html);

        $output->writeln('Done!');

        return Command::SUCCESS;
    }
}
