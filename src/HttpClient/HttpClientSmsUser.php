<?php

declare(strict_types=1);

namespace App\HttpClient;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClientSmsUser
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $smsClient)
    {
        $this->client = $smsClient;
    }

    /**
     * @throws ClientExceptionInterface|TransportExceptionInterface
     */
    public function sendSms(string $phone, string $message): void
    {
        $postData = [
            'receiver' => $phone,
            'body' => $message
        ];
        $postJson = \json_encode($postData);

        $this->client->request(
            Request::METHOD_POST,
            '/api/messages',
            [
                'body' => $postJson,
            ]
        );
    }
}
