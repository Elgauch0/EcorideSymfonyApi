<?php

namespace App\Factory;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Itinerary;
use App\Model\ReservationDto;
use Doctrine\ORM\EntityManagerInterface;

class ReservationFactory
{
    /**
     * Constructor to inject dependencies.
     */
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     * Creates a new Reservation entity from a ReservationDto.
     *
     * @param ReservationDto $dto The data transfer object containing reservation details.
     * @return Reservation The newly created Reservation entity.
     * @throws \InvalidArgumentException If the client or itinerary is not found,
     * or if there are not enough available seats.
     */
    public function createFromDto(ReservationDto $dto): Reservation
    {
        // 1. Retrieve the User entity (client) based on the ID from the DTO.
        $client = $this->em->getRepository(User::class)->find($dto->clientId);
        if (!$client) {
            throw new \InvalidArgumentException("Client introuvable avec l'ID: " . $dto->clientId);
        }

        // 2. Retrieve the Itinerary entity (trip) based on the ID from the DTO.
        $itinerary = $this->em->getRepository(Itinerary::class)->find($dto->itineraryId);
        if (!$itinerary) {
            throw new \InvalidArgumentException("Itinéraire introuvable avec l'ID: " . $dto->itineraryId);
        }

        // 3. Check for available seats before creating the reservation.
        // This ensures we don't overbook the itinerary.
        if ($itinerary->getPlaces() < $dto->seatsReserved) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Pas assez de places disponibles sur cet itinéraire (places disponibles: %d, demandées: %d).",
                    $itinerary->getPlaces(),
                    $dto->seatsReserved
                )
            );
        }

        // --- NOUVEAU : Diminuer le nombre de places disponibles sur l'itinéraire ---
        $newAvailablePlaces = $itinerary->getPlaces() - $dto->seatsReserved;
        $itinerary->setPlaces($newAvailablePlaces);
        $this->em->persist($itinerary);
        // -----------------------------------------------------------------------


        $reservation = new Reservation();

        // Map the DTO properties to the Reservation entity's setters.
        $reservation->setClientId($client);
        $reservation->setItinerary($itinerary);
        $reservation->setSeatsReserved($dto->seatsReserved);
        $reservation->setDateReservation(new \DateTimeImmutable());
        $reservation->setIsCancelled(false);

        return $reservation;
    }
}
