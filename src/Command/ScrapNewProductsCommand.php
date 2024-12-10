<?php

namespace App\Command;

use App\Entity\Flavour;
use App\Entity\Product;
use App\Entity\RankedProduct;
use App\Entity\WeekScrap;
use App\Repository\ProductRepository;
use App\Repository\RankedProductRepository;
use App\Repository\TierListRepository;
use App\Repository\WeekScrapRepository;
use App\Service\Crawling\ProductCrawler;
use App\Service\File\Uploader;
use App\Service\Scraping\Scraper;
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
        private readonly Scraper $scraper,
        private readonly ProductCrawler $productCrawler,
        private readonly ProductRepository $productRepository,
        private readonly TierListRepository $tierListRepository,
        private readonly RankedProductRepository $rankedProductRepository,
        private readonly WeekScrapRepository $weekScrapRepository,
        private readonly Uploader $uploader,
        private readonly string $baseUrl,
        private readonly string $allProductsPath,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tierLists = $this->tierListRepository->findAll();
        $weekScrap = $this->getAssociatedWeekScrap();
        $productAdded = [];

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

                try {
                    $localUrl = $this->uploadImage($productDTO->name, $productDTO->originalImageUrl, $output);
                    if (null !== $localUrl) {
                        $productDTO->imageUrl = $localUrl;
                    }
                } catch (\Exception $e) {
                    $output->writeln(sprintf('Error uploading image of %s: %s', $productDTO->name, $e->getMessage()));
                }

                $isNewFlavour = null !== $productDTO->flavour;
                $newProduct = $isNewFlavour ? Flavour::createFromDTO($productDTO) : Product::createFromDTO($productDTO);

                $this->productRepository->save($newProduct, false);
                $productAdded[] = $newProduct->getName();

                if (!$isNewFlavour) {
                    continue;
                }

                foreach ($tierLists as $tierList) {
                    $this->rankedProductRepository->save(new RankedProduct($tierList, $newProduct), false);
                }
            }
        }

        if (null !== $weekScrap) {
            $weekScrap
                ->setDetails($productAdded)
                ->setProductAdded(count($productAdded));
            $this->weekScrapRepository->save($weekScrap);
        }

        $this->productRepository->save();

        return Command::SUCCESS;
    }

    private function uploadImage(string $name, string $imageUrl, OutputInterface $output): ?string
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

    private function getAssociatedWeekScrap(): ?WeekScrap
    {
        $weekScrap = $this->weekScrapRepository->findLast();

        if (null === $weekScrap) {
            throw new \Exception('Current Scrap not found');
        }

        $interval = date_diff($weekScrap->getDate(), new \DateTime());
        $interval = (new \DateTime())->setTimestamp(0)->add($interval)->getTimestamp() / 60;
        if ($interval > 2) {
            return null;
        }

        return $weekScrap;
    }
}
