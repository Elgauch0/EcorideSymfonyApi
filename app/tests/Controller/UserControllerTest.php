<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UserControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'user@ecoride.com',
                'password' => 'password'
            ])
        );

        self::assertResponseIsSuccessful();
    }


    public function testAdminWithToken(): void
    {
        $client = static::createClient();
        $userRepo = static::getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneByEmail('user@ecoride.com');

        $token = static::getContainer()->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => $user->getUserIdentifier()]);

        $client->request('GET', '/api/user', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ]);

        self::assertResponseIsSuccessful();
    }
}
