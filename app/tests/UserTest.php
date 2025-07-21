<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserProperties(): void
    {
        $user = new User();
        $user->setFirstname("Anass");
        $user->setLastname("El Majjaoui");
        $user->setEmail("anass@example.com");
        $user->setAdress("61 avenue de la victoire 95000");

        $this->assertSame("Anass", $user->getFirstName());
        $this->assertSame("El Majjaoui", $user->getLastName());
        $this->assertSame("anass@example.com", $user->getEmail());
        $this->assertSame("61 avenue de la victoire 95000", $user->getAdress());
    }


    public function testUserIsEmpty(): void
    {
        $user = new User();
        $this->assertEmpty($user->getFirstName());
        $this->assertEmpty($user->getLastName());
        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getAdress());
    }
}
