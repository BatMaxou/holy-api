<?php

namespace App\Mock\Service\Scraping;

use App\Enum\HolyProductRange;
use App\Service\Scraping\Interface\ScraperInterface;

class ScraperMock implements ScraperInterface
{
    public function __construct(
        private readonly string $mockPath,
    )
    {
    }

    public function scrap(string $url): string
    {
        $html = '<body>';
        $html .= '<div class="product-facet__main--inner">'.$this->getMockedContent(HolyProductRange::ENERGY).'</div>';
        $html .= '<div class="product-facet__main--inner">'.$this->getMockedContent(HolyProductRange::ICE_TEA).'</div>';
        $html .= '<div class="product-facet__main--inner">'.$this->getMockedContent(HolyProductRange::HYDRATATION).'</div>';
        $html .= '</body>';

        return $html;
    }

    private function getMockedContent(HolyProductRange $holyProductRange): string
    {
        return file_get_contents(sprintf(
            '%s/%s.html',
            $this->mockPath,
            $holyProductRange->value,
        )) ?: '';
    }
}
