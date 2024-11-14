<?php

namespace App\Service\Scraping\Interface;

interface ScraperInterface
{
    public function scrap(string $url): string;
}
