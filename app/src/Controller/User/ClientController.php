<?php

namespace App\Controller\User;


use App\Entity\User;
use App\Model\VehicleDto;
use App\Model\ReservationDto;
use App\Factory\VehicleFactory;
use App\Factory\ReservationFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/user')]
final class ClientController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,

    ) {}




    #[Route('/addvehicle', name: 'add_vehicle', methods: ['POST'])]
    public function addVehicule(#[MapRequestPayload] VehicleDto $vehicleDto, VehicleFactory $vehicleFactory): JsonResponse
    {

        $vehicle = $vehicleFactory->createFromDto($vehicleDto);

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $vehicle->setDriver($user);


        if (!in_array('ROLE_CHAUFFEUR', $user->getRoles(), true)) {
            $user->setRoles(array_unique([...$user->getRoles(), 'ROLE_DRIVER']));
            $this->em->persist($user);
        }

        $this->em->persist($vehicle);
        $this->em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }




    #[Route('/addreservation', name: 'add_reservation', methods: ['POST'])]
    public function addReservation(#[MapRequestPayload]  ReservationDto $reservationDto, ReservationFactory $reservationFactory): JsonResponse
    {
        $reservation = $reservationFactory->createFromDto($reservationDto);
        $this->em->persist($reservation);
        $this->em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }



    #[Route('/getuser/{id}', name: 'get_user', methods: ['GET'], requirements: ['id' => Requirement::POSITIVE_INT])]
    public function getUserDetail(User $user, SerializerInterface $serializer): JsonResponse
    {
        $jsonContent = $serializer->serialize($user, 'json', ['groups' => ['user:read']]);
        return new JsonResponse($jsonContent, JsonResponse::HTTP_OK, [], true);
    }
}
