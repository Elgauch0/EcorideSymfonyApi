<?php

namespace App\Tests;

use App\Entity\Itinerary;
use App\Entity\Vehicle;
use PHPUnit\Framework\TestCase;

class ItineraryTest extends TestCase
{
    public function testItineraryProperties(): void
    {
        $itinerary = new Itinerary();
        $vehicle = $this->createMock(Vehicle::class);

        $itinerary->setDuration(120);
        $itinerary->setPrice(25);
        $itinerary->setDatetime(new \DateTimeImmutable('2025-07-21 14:00'));
        $itinerary->setIsStarted(true);
        $itinerary->setIsFinished(false);
        $itinerary->setIsCancelled(false);
        $itinerary->setPlaces(3);
        $itinerary->setDepartureCity("Paris");
        $itinerary->setArrivalCity("Lyon");
        $itinerary->setVehicule($vehicle);

        $this->assertSame(120, $itinerary->getDuration());
        $this->assertSame(25, $itinerary->getPrice());
        $this->assertEquals(new \DateTimeImmutable('2025-07-21 14:00'), $itinerary->getDatetime());
        $this->assertTrue($itinerary->isStarted());
        $this->assertFalse($itinerary->isFinished());
        $this->assertFalse($itinerary->isCancelled());
        $this->assertSame(3, $itinerary->getPlaces());
        $this->assertSame("Paris", $itinerary->getDepartureCity());
        $this->assertSame("Lyon", $itinerary->getArrivalCity());
        $this->assertSame($vehicle, $itinerary->getVehicule());
    }

    public function testItineraryIsEmpty(): void
    {
        $itinerary = new Itinerary();

        $this->assertEmpty($itinerary->getDuration());
        $this->assertEmpty($itinerary->getPrice());
        $this->assertEmpty($itinerary->getDatetime());
        $this->assertEmpty($itinerary->isStarted());
        $this->assertEmpty($itinerary->isFinished());
        $this->assertEmpty($itinerary->isCancelled());
        $this->assertEmpty($itinerary->getPlaces());
        $this->assertEmpty($itinerary->getDepartureCity());
        $this->assertEmpty($itinerary->getArrivalCity());
        $this->assertEmpty($itinerary->getVehicule());
    }
}
