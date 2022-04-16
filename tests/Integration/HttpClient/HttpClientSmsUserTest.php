<?php

declare(strict_types=1);

namespace App\Tests\Integration\HttpClient;

use App\HttpClient\HttpClientSmsUser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\Exception\ClientException;

class HttpClientSmsUserTest extends KernelTestCase
{
    private ?HttpClientSmsUser $client;

    protected function runTest(): void
    {
        $this->markTestSkipped('Skipped test for GitHub Actions');
    }

    protected function setup(): void
    {
        parent::setUp();
        $container = static::getContainer();

        $this->client = $container->get(HttpClientSmsUser::class);
    }

    public function testSendSmsNotification()
    {
        $this->client->sendSms('1234567890', 'This is some content');

        self::assertTrue(True);
    }

    public function testWrongReceiver()
    {
        self::expectException(ClientException::class);
        self::expectExceptionMessage(
            'HTTP/1.1 400 Bad Request returned for'
        );

        $this->client->sendSms('askdfash', 'This is some content');
    }
}
