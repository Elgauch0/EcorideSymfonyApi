<?php

namespace App\Factory;

use App\Entity\Vehicle;
use App\Entity\Itinerary;
use App\Model\ItineraryDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class ItineraryFactory
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}




    public function createFromDto(ItineraryDto $itineraryDto): Itinerary
    {
        // Recherche le véhicule correspondant à l'ID fourni dans le DTO
        $vehicule = $this->em->getRepository(Vehicle::class)->find($itineraryDto->vehiculeId);

        // Si le véhicule n'est pas trouvé, lance une exception
        if (!$vehicule) {
            throw new NotFoundHttpException('Véhicule non trouvé.');
        }

        if ($itineraryDto->places !== null && $itineraryDto->places > $vehicule->getSeatsAvailable()) {
            throw new BadRequestHttpException(
                sprintf(
                    'Le nombre de places demandé (%d) dépasse la capacité du véhicule (%d).',
                    $itineraryDto->places,
                    $vehicule->getSeatsAvailable()
                )
            );
        }

        $itinerary = new Itinerary();
        // Hydratation de l'entité avec les données du DTO
        $itinerary->setDatetime(new \DateTimeImmutable($itineraryDto->datetime));
        $itinerary->setDuration($itineraryDto->duration);
        $itinerary->setPrice($itineraryDto->price);
        $itinerary->setIsStarted(false);
        $itinerary->setIsFinished(false);
        $itinerary->setIsCancelled(false);
        $itinerary->setPlaces($itineraryDto->places ?? $vehicule->getSeatsAvailable());
        $itinerary->setDepartureCity($itineraryDto->departureCity);
        $itinerary->setArrivalCity($itineraryDto->arrivalCity);
        $itinerary->setVehicule($vehicule);

        return $itinerary;
    }
}
