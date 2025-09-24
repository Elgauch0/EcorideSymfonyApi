<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ApiFirewallTest extends WebTestCase
{
    public function testUnauthorized(): void
    {
        $client = static::createClient();
        $client->request(
            'Get',
            '/api/admin',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'Accept' => 'application/json'
            ],
        );

        self::assertResponseStatusCodeSame(401);
    }


    public function testPublic(): void
    {
        $client = static::createClient();
        $client->request(
            'Get',
            '/api/guest',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'Accept' => 'application/json'
            ],
        );

        self::assertResponseIsSuccessful();
    }
}
