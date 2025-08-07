<?php

namespace App\Tests;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Itinerary;
use PHPUnit\Framework\TestCase;

class ReservationTest extends TestCase
{
    public function testReservationProperties(): void
    {
        $reservation = new Reservation();
        $client = $this->createMock(User::class);
        $itinerary = $this->createMock(Itinerary::class);
        $date = new \DateTimeImmutable('2025-07-22 08:30');

        $reservation->setDateReservation($date);
        $reservation->setSeatsReserved(2);
        $reservation->setClientId($client);
        $reservation->setIsCancelled(false);
        $reservation->setItinerary($itinerary);

        $this->assertSame($date, $reservation->getDateReservation());
        $this->assertSame(2, $reservation->getSeatsReserved());
        $this->assertSame($client, $reservation->getClientId());
        $this->assertFalse($reservation->isCancelled());
        $this->assertSame($itinerary, $reservation->getItinerary());
    }

    public function testReservationDefaults(): void
    {
        $reservation = new Reservation();

        $this->assertInstanceOf(\DateTimeImmutable::class, $reservation->getDateReservation());
        $this->assertEmpty($reservation->getSeatsReserved());
        $this->assertEmpty($reservation->getClientId());
        $this->assertEmpty($reservation->isCancelled());
        $this->assertEmpty($reservation->getItinerary());
    }
}
