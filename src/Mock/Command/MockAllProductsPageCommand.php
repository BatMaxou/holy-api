<?php

namespace App\Mock\Command;

use App\Enum\HolyProductRange;
use App\Service\Scraping\Scraper;
use Symfony\Component\DomCrawler\Crawler;
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
        private readonly string $baseUrl,
        private readonly string $allProductsPath,
        private readonly string $mockPath,
    ){
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Mocking "all products" page:');

        $html = $this->scraper->scrap($this->baseUrl.$this->allProductsPath);
        $crawler = new Crawler($html);

        $crawler->filter('.product-facet__main--inner')->each(function (Crawler $node, $i) use ($output) {
            $output->writeln(sprintf('Mocking %s...', $node->filter('h3')->text()));

            file_put_contents(
                match ($i) {
                    0 => $this->mockPath.HolyProductRange::DISCOVER_PACK->value.'.html',
                    1 => $this->mockPath.HolyProductRange::ENERGY->value.'.html',
                    2 => $this->mockPath.HolyProductRange::ICE_TEA->value.'.html',
                    3 => $this->mockPath.HolyProductRange::HYDRATATION->value.'.html',
                    4 => $this->mockPath.HolyProductRange::SHAKER->value.'.html',
                    5 => $this->mockPath.HolyProductRange::MERCHANDISING->value.'.html',
                    default => $this->mockPath.'default.html',
                }, str_replace('\n', '', $node->html())
            );
        });

        $output->writeln('Done!');

        return Command::SUCCESS;
    }
}
