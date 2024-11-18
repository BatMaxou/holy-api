<?php

namespace App\Command;

use App\Entity\Flavour;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\Crawling\ProductCrawler;
use App\Service\File\Uploader;
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
        private readonly ProductRepository $productRepository,
        private readonly Uploader $uploader,
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
            $output->writeln(sprintf('Searching for new products from %s...', $productRangeDTO->webSiteName));
            $this->uploader->setCurrentDirectory($productRangeDTO->productRange->value);

            foreach ($productRangeDTO->items as $productDTO) {
                if (!empty($this->productRepository->findBy(['name' => $productDTO->name]))) {
                    continue;
                }

                $output->writeln(sprintf('New product detected: %s', $productDTO->name));
                $isNewFlavour = null !== $productDTO->flavour;

                $newProduct = $isNewFlavour ? Flavour::createFromDTO($productDTO) : Product::createFromDTO($productDTO);

                try {
                    $this->uploadImage($productDTO->name, $productDTO->imageUrl, $output);
                } catch (\Exception $e) {
                    $output->writeln(sprintf('Error uploading image of %s: %s', $productDTO->name, $e->getMessage()));
                }

                $this->productRepository->save($newProduct, false);
            }
        }

        $this->productRepository->save();

        return Command::SUCCESS;
    }

    private function uploadImage(string $name, string $imageUrl, OutputInterface $output): bool
    {
        $formattedName = strtolower($name);
        $formattedName = str_replace([' ', "'"], '_', $formattedName);
        $formattedName = str_replace(['É', 'È', 'Ë', 'Ê', 'é', 'è', 'ë', 'ê'], 'e', $formattedName);
        $formattedName = str_replace(['À', 'à'], 'a', $formattedName);
        $formattedName = str_replace(['Ô', 'ô'], 'o', $formattedName);
        $formattedName = str_replace(['Û', 'û'], 'u', $formattedName);
        $formattedName = str_replace(['Î', 'î'], 'i', $formattedName);
        $formattedName = str_replace(['Ï', 'ï'], 'i', $formattedName);
        $formattedName = str_replace(['Ç', 'ç'], 'c', $formattedName);
        $formattedName = str_replace(['Æ', 'æ'], 'ae', $formattedName);
        $formattedName = str_replace(['Œ', 'œ'], 'oe', $formattedName);
        $formattedName = str_replace(['(', '[', '{', '}', ']', ')'], '', $formattedName);

        $output->writeln(sprintf('Uploading image of %s...', $formattedName));

        return $this->uploader->uploadFile($formattedName, $imageUrl);
    }
}
