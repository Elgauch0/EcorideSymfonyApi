<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ItineraryControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();

        $postParams = [
            'depart' => 'Paris', // Changed from 'departure' to 'depart'
            'destination' => 'Lyon', // Changed from 'arrival' to 'destination'
            'date' => '2025-08-15',
        ];

        $crawler = $client->request(
            'POST',
            '/api/itinerary/search',
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
            'depart' => 'Marseille', // Changed from 'departure' to 'depart'
            'destination' => 'Nice', // Changed from 'arrival' to 'destination'
            'date' => '2025-10-10',
        ];

        $crawler = $client->request(
            'POST',
            '/api/itinerary/search',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($postParams)
        );

        self::assertResponseIsSuccessful();
    }
}
