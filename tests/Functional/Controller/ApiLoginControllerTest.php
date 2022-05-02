<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiLoginControllerTest extends WebTestCase
{
    protected function runTest(): void
    {
        $this->markTestSkipped('Skipped test for GitHub Actions');
    }

    public function testLogin(): void
    {
        $client = static::createClient();
        $body = [
            "email" => "marceldobrica66@gmail.com",
            "password" => "Mi5@sua1"
        ];
        $crawler = $client->jsonRequest('POST', 'http://localhost/api/login', $body);

        $this->assertResponseIsSuccessful();
        $decodedContent = \json_decode($client->getResponse()->getContent(), true);
        $token = $decodedContent['token'];
        $emailResponse = $decodedContent['user'];

        $crawler = $client->request('GET', 'http://internship.local/api/programme', [], [], [
            'HTTP_X-AUTH-TOKEN' => $token,
            'HTTP_ACCEPT' => 'application/json'
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertEquals($body['email'], $emailResponse);

        $client->request('DELETE', 'http://internship.local/api/users/delete/1', [], [], [
            'HTTP_X-AUTH-TOKEN' => $token,
            'HTTP_ACCEPT' => 'application/json',
        ]);
    }
}
