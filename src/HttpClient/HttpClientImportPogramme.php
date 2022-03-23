<?php

declare(strict_types=1);

namespace App\HttpClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClientImportPogramme
{
    public string $http_address = 'http://evozon-internship-data-wh.herokuapp.com/api/sport-programs';

    public string $http_protocol = 'GET';

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchData(): array
    {
        $response = $this->client->request(
            $this->http_protocol,
            $this->http_address
        );
        $content = $response->toArray();

        return $content['data'];
    }
}
