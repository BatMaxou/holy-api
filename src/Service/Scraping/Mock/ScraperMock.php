<?php

namespace App\Service\Scraping\Mock;

use App\Enum\ProductRangeEnum;
use App\Service\Scraping\Interface\ScraperInterface;

class ScraperMock implements ScraperInterface
{
    private const MOCK_PRODUCT_HTML_PATERN = '<div class="product-facet__main--inner">{{content}}</div>';

    public function __construct(
        private readonly string $mockPath,
    ) {
    }

    public function scrap(string $url): string
    {
        $html = '<body>';
        $html .= $this->applyMockProductHtmlPatern($this->getMockedContent(ProductRangeEnum::DISCOVER_PACK));
        $html .= $this->applyMockProductHtmlPatern($this->getMockedContent(ProductRangeEnum::ENERGY));
        $html .= $this->applyMockProductHtmlPatern($this->getMockedContent(ProductRangeEnum::ICED_TEA));
        $html .= $this->applyMockProductHtmlPatern($this->getMockedContent(ProductRangeEnum::HYDRATION));
        $html .= $this->applyMockProductHtmlPatern($this->getMockedContent(ProductRangeEnum::SHAKER));
        $html .= $this->applyMockProductHtmlPatern($this->getMockedContent(ProductRangeEnum::MERCH));
        $html .= '</body>';

        return $html;
    }

    private function getMockedContent(ProductRangeEnum $productRange): string
    {
        return file_get_contents(sprintf(
            '%s/%s.html',
            $this->mockPath,
            $productRange->value,
        )) ?: '';
    }

    public function applyMockProductHtmlPatern(string $content): string
    {
        return str_replace('{{content}}', $content, self::MOCK_PRODUCT_HTML_PATERN);
    }
}
