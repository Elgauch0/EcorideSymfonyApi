<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Vehicle;
use PHPUnit\Framework\TestCase;


class VehicleTest extends TestCase
{
    public function testVehicleProperties(): void
    {

        $vehicle = new Vehicle();
        $user = $this->createMock(User::class);


        $vehicle->setLicencePlate("AB-123-CD");
        $vehicle->setRegistrationDate(new \DateTimeImmutable('2023-01-01'));
        $vehicle->setSeatsAvailable(4);
        $vehicle->setIsSmockingAlowed(true);
        $vehicle->setIsPetsAlowed(false);
        $vehicle->setModel("Tesla Model Y");
        $vehicle->setIsGreen(true);
        $vehicle->setDriver($user);


        $this->assertSame("AB-123-CD", $vehicle->getLicencePlate());
        $this->assertEquals(new \DateTimeImmutable('2023-01-01'), $vehicle->getRegistrationDate());
        $this->assertSame(4, $vehicle->getSeatsAvailable());
        $this->assertTrue($vehicle->isSmockingAlowed());
        $this->assertFalse($vehicle->isPetsAlowed());
        $this->assertSame($user, $vehicle->getDriver());
        $this->assertSame("Tesla Model Y", $vehicle->getModel());
        $this->assertTrue($vehicle->isGreen());
    }

    public function testVehicleIsEmpty(): void
    {
        $vehicle = new Vehicle();

        $this->assertEmpty($vehicle->getLicencePlate());
        $this->assertEmpty($vehicle->getRegistrationDate());
        $this->assertEmpty($vehicle->getSeatsAvailable());
        $this->assertEmpty($vehicle->isSmockingAlowed());
        $this->assertEmpty($vehicle->isPetsAlowed());
        $this->assertEmpty($vehicle->getModel());
        $this->assertEmpty($vehicle->isGreen());
    }
}
