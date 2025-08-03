<?php

namespace App\Controller\Driver;


use App\Factory\VehicleFactory;
use App\Model\VehicleDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/driver')]
final class VehicleController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private VehicleFactory $vehicleFactory
    ) {}




    #[Route('/addvehicle', name: 'add_vehicle', methods: ['POST'])]
    public function addVehicule(#[MapRequestPayload] VehicleDto $vehicleDto): JsonResponse
    {
        $vehicle = $this->vehicleFactory->createFromDto($vehicleDto);
        // important changer le role du user 
        // $user = $vehicle->getDriver();
        // if (!in_array('ROLE_CHAUFFEUR', $user->getRoles())) {
        //     $user->setRoles(array_unique([...$user->getRoles(), 'ROLE_CHAUFFEUR']));
        //     $this->em->persist($user);
        // }

        $this->em->persist($vehicle);
        $this->em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }
}
