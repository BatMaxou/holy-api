<?php

namespace App\Service\Scraping;

use App\Service\Scraping\Interface\ScraperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Scraper implements ScraperInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
    )
    {
    }

    public function scrap(string $url): string
    {
        $response = $this->client->request(Request::METHOD_GET, $url, [
            'headers' => [
                'accept-language' => 'fr-FR',
            ],
        ]);

        return $response->getContent();
    }
}
