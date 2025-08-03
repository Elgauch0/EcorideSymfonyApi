<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UserControllerTest extends WebTestCase
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
            '/api/public',
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
