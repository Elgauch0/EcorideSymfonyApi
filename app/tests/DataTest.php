<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    public function testSomething(): void
    {
        $user = new User();
        $user->setAdress('61 avenue de la victoire 95000');


        $this->assertSame("61 avenue de la victoire 95000", $user->getAdress());
    }
}
