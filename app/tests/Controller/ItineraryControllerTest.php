<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ItineraryControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();

        $postParams = [
            'depart' => 'Paris',
            'destination' => 'Lyon',
            'date' => '2025-08-15',
        ];

        $client->request(
            'POST',
            '/api/guest/search',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($postParams)
        );

        self::assertResponseIsSuccessful();
    }

    public function testSearchForSpecificDate(): void
    {
        $client = static::createClient();

        $postParams = [
            'depart' => 'Marseille',
            'destination' => 'Nice',
            'date' => '2025-10-10',
        ];

        $client->request(
            'POST',
            '/api/guest/search',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($postParams)
        );

        self::assertResponseIsSuccessful();
    }
}
