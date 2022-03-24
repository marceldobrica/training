<?php

declare(strict_types=1);

namespace App\HttpClient;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClientImportPogramme
{
    private HttpClientInterface $client;

    private string $httpImportAddress;

    public function __construct(HttpClientInterface $client, string $httpImportAddress)
    {
        $this->client = $client;
        $this->httpImportAddress = $httpImportAddress;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fetchData(): array
    {
        $response = $this->client->request(
            Request::METHOD_GET,
            $this->httpImportAddress
        );
        $content = $response->toArray();

        return $content['data'];
    }
}
