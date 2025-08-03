<?php

namespace App\Factory;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Model\VehicleDto;
use Doctrine\ORM\EntityManagerInterface;

class VehicleFactory
{
    public function __construct(private EntityManagerInterface $em) {}

    public function createFromDto(VehicleDto $dto): Vehicle
    {
        $driver = $this->em->getRepository(User::class)->find($dto->driverId);
        if (!$driver) {
            throw new \InvalidArgumentException("Conducteur introuvable.");
        }

        $vehicle = new Vehicle();
        $vehicle->setDriver($driver);
        $vehicle->setLicencePlate($dto->licencePlate);
        $vehicle->setRegistrationDate(new \DateTimeImmutable($dto->registrationDate));
        $vehicle->setSeatsAvailable($dto->seatsAvailable);
        $vehicle->setIsSmockingAlowed($dto->isSmockingAlowed);
        $vehicle->setIsPetsAlowed($dto->isPetsAlowed);
        $vehicle->setModel($dto->model);
        $vehicle->setIsGreen($dto->isGreen);

        return $vehicle;
    }
}
