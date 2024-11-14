<?php

namespace App\Command;

use App\Mock\Service\Scraping\ScraperMock;
use App\Service\Scraping\Interface\ScraperInterface;
use App\Service\Scraping\Scraper;
use Symfony\Component\DomCrawler\Crawler;
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
    private OutputInterface $output;

    public function __construct(
        private readonly ScraperMock $scraper,
        private readonly string $baseUrl,
        private readonly string $allProductsPath,
    ){
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $this->output->writeln('Scraping new products...');

        $html = $this->scraper->scrap($this->baseUrl.$this->allProductsPath);
        
        $crawler = new Crawler($html);
        $infos = $crawler->filter('.product-facet__main--inner')->each($this->getInfosFromScrapedContent(...));

        return Command::SUCCESS;
    }

    private function getInfosFromScrapedContent(Crawler $node): array
    {
        $h3 = $node->filter('h3')->text();
        $this->output->writeln(sprintf('Scraping %s...', $h3));

        $items = $node->filter('.product-item')->each($this->getItemInfos(...));

        return [
            'name' => $h3,
            'items' => $items,
        ];
    }

    private function getItemInfos(Crawler $node): array
    {
        return [
            'name' => $this->getText('h4', $node),
            'price' => $this->parsePrice($this->getText('.price', $node)),
        ];
    }

    private function getText(string $cssSelector, Crawler $node): string
    {
        return $node->filter($cssSelector)->text();
    }

    private function parsePrice(string $price): int
    {
        return (int) str_replace(['€', ','], '', strstr($price, '€'));
    }
}
