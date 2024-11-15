<?php

namespace App\Service\Scraping\Mock;

use App\Enum\ProductRange;
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
        $html .= $this->applyMockProductHtmlPatern($this->getMockedContent(ProductRange::DISCOVER_PACK));
        $html .= $this->applyMockProductHtmlPatern($this->getMockedContent(ProductRange::ENERGY));
        $html .= $this->applyMockProductHtmlPatern($this->getMockedContent(ProductRange::ICED_TEA));
        $html .= $this->applyMockProductHtmlPatern($this->getMockedContent(ProductRange::HYDRATION));
        $html .= $this->applyMockProductHtmlPatern($this->getMockedContent(ProductRange::SHAKER));
        $html .= $this->applyMockProductHtmlPatern($this->getMockedContent(ProductRange::MERCH));
        $html .= '</body>';

        return $html;
    }

    private function getMockedContent(ProductRange $ProductRange): string
    {
        return file_get_contents(sprintf(
            '%s/%s.html',
            $this->mockPath,
            $ProductRange->value,
        )) ?: '';
    }

    public function applyMockProductHtmlPatern(string $content): string
    {
        return str_replace('{{content}}', $content, self::MOCK_PRODUCT_HTML_PATERN);
    }
}
