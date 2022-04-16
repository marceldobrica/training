<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    protected function runTest(): void
    {
        $this->markTestSkipped('Skipped test for GitHub Actions');
    }

    public function testInputExists(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Please log in!');
        self::assertSame($crawler->filter('input[name=_username]')->count(), 1);
        self::assertSame($crawler->filter('input[name=_password]')->count(), 1);
        self::assertSame($crawler->filter('button[type=submit]')->count(), 1);
    }
}
