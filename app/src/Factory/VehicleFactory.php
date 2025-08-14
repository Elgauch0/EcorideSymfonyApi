<?php

namespace App\Factory;

use App\Entity\Vehicle;
use App\Model\VehicleDto;
use Doctrine\ORM\EntityManagerInterface;

class VehicleFactory
{
    public function __construct(private EntityManagerInterface $em) {}

    public function createFromDto(VehicleDto $dto): Vehicle
    {

        $vehicle = new Vehicle();
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
