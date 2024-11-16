<?php

namespace App\Command;

use App\Service\Crawling\ProductCrawler;
use App\Service\Scraping\Mock\ScraperMock;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:scrap:products',
    description: 'Add new products from the website',
)]
class ScrapNewProductsCommand extends Command
{
    public function __construct(
        private readonly ScraperMock $scraper,
        private readonly ProductCrawler $productCrawler,
        private readonly string $baseUrl,
        private readonly string $allProductsPath,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->productCrawler->setCurrentOutput($output);
        $output->writeln('Scraping new products...');

        $html = $this->scraper->scrap(sprintf('%s.%s', $this->baseUrl, $this->allProductsPath));
        $infos = $this->productCrawler->collectAllProductInfos($html);

        foreach ($infos as $productRangeDTO) {
            // conditionnal Entity / Uploader / Output
        }

        return Command::SUCCESS;
    }
}
