<?php

namespace App\Service\Crawling;

use App\DTO\ScrapProductDTO;
use App\DTO\ScrapProductRangeDTO;
use App\Enum\ProductRangeEnum;
use Symfony\Component\DomCrawler\Crawler;
use App\Service\Guesser\ProductRangeGuesser;
use App\Utils;
use Symfony\Component\Console\Output\OutputInterface;

class ProductCrawler
{
    private const MOCK_HTML_TEMPLATE_PATH_PATERN = '{{mockPath}}/{{productRangeName}}.html';

    /**
     * @var array{
     *  enum: ProductRangeEnum,
     *  webSiteName: string,
     * }
     */
    private array $currentExploredProductRange;
    private string $currentHtmlTemplatePathPatern;
    private ?OutputInterface $currentOutput;

    public function __construct(
        private readonly ProductRangeGuesser $productRangeGuesser,
        private readonly string $mockPath,
    ) {
        $this->currentOutput = null;
        $this->currentHtmlTemplatePathPatern = str_replace('{{mockPath}}', $this->mockPath, self::MOCK_HTML_TEMPLATE_PATH_PATERN);
    }

    public function setCurrentOutput(OutputInterface $output): static
    {
        $this->currentOutput = $output;

        return $this;
    }

    /**
     * @return ScrapProductRangeDTO[]
     */
    public function collectAllProductInfos(string $html): array
    {
        if (null === $this->currentOutput) {
            throw new \Exception('Command output not set');
        }

        $crawler = new Crawler($html);

        /** @var ScrapProductRangeDTO[] $infos */
        $infos = $crawler->filter(Utils::PRODUCT_RANGE_CSS_SELECTOR)->each($this->getInfosFromScrapedContent(...));

        return $infos;
    }

    private function getInfosFromScrapedContent(Crawler $node): ScrapProductRangeDTO
    {
        $productRangeName = $node->filter('h3')->text();
        $productRangeGuessed = $this->productRangeGuesser->guess(strtolower($productRangeName));

        $this->currentExploredProductRange = [
            'enum' => $productRangeGuessed,
            'webSiteName' => $productRangeName,
        ];

        $this->currentOutput?->writeln(sprintf('Scraping %s...', $productRangeName));
        $items = $node->filter('.product-item')->each($this->getItemInfos(...));

        return ScrapProductRangeDTO::createFrom([
            'productRange' => $productRangeGuessed,
            'webSiteName' => $productRangeName,
            'items' => $items,
        ]);
    }

    private function getItemInfos(Crawler $node): ScrapProductDTO
    {
        $itemName = $this->getText($node, 'h4');
        $itemImageUrl = $this->getImageUrl($node);

        return ScrapProductDTO::createFrom([
            'name' => $itemName,
            'productRange' => $this->currentExploredProductRange['enum'],
            ...(
                in_array($this->currentExploredProductRange['enum'], ProductRangeEnum::getAllWithFlavour())
                ? ['flavour' => $this->parseFlavour($this->getText($node, Utils::PRODUCT_FLAVOUR_CSS_SELECTOR))]
                : []
            ),
            'price' => $this->parsePrice($this->getText($node, Utils::PRODUCT_PRICE_CSS_SELECTOR)),
            'originalImageUrl' => $itemImageUrl,
        ]);
    }

    public function applyMockPathPatern(string $productRangeName): string
    {
        return str_replace('{{productRangeName}}', $productRangeName, $this->currentHtmlTemplatePathPatern);
    }

    private function getText(Crawler $node, string $cssSelector): string
    {
        return $node->filter($cssSelector)->text();
    }

    private function getImageUrl(Crawler $node, ?string $cssSelector = ''): string
    {
        $selector = 'img';
        if ($cssSelector) {
            $selector .= sprintf(' %s', $cssSelector);
        }

        $src = $node->filter($selector)->attr('src') ?? null;

        return null === $src ? '' : sprintf('https:%s', $src);
    }

    private function parseFlavour(string $raw): string
    {
        if (!isset($this->currentExploredProductRange)) {
            throw new \LogicException('No product range set');
        }

        $matches = [];
        $regex = sprintf(Utils::PRODUCT_FLAVOUR_REGEX_PATERN, $this->currentExploredProductRange['webSiteName']);

        return preg_match($regex, $raw, $matches) ? $matches[1] : '';
    }

    private function parsePrice(string $price): int
    {
        return (int) str_replace(['€', ','], '', strstr($price, '€') ?: '0');
    }

    public function writeMockedContent(string $html): void
    {
        if (null === $this->currentOutput) {
            throw new \Exception('Command output not set');
        }

        $crawler = new Crawler($html);
        $crawler->filter(Utils::PRODUCT_RANGE_CSS_SELECTOR)->each($this->fillMockHtmlTemplate(...));
    }

    private function fillMockHtmlTemplate(Crawler $node, int $i): void
    {
        $this->currentOutput?->writeln(sprintf('Filling template of %s...', $node->filter('h3')->text()));

        file_put_contents(
            $this->applyMockPathPatern(match ($i) {
                0 => ProductRangeEnum::DISCOVER_PACK->value,
                0 => ProductRangeEnum::DEFAULT->value,
                1 => ProductRangeEnum::ENERGY->value,
                2 => ProductRangeEnum::ICED_TEA->value,
                3 => ProductRangeEnum::HYDRATION->value,
                4 => ProductRangeEnum::MILKSHAKE->value,
                5 => ProductRangeEnum::SHAKER->value,
                6 => ProductRangeEnum::MERCH->value,
                default => 'default',
            }),
            str_replace('\n', '', $node->html())
        );
    }
}
