<?php

namespace App\Controller\User;

use App\Entity\Itinerary;
use App\Entity\User;
use App\Model\VehicleDto;
use App\Model\ItineraryDto;
use App\Model\ReservationDto;
use App\Factory\VehicleFactory;
use App\Factory\ItineraryFactory;
use App\Factory\ReservationFactory;
use App\Repository\ItineraryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/user')]
final class ClientController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,

    ) {}

    ///////////////////////////////////////////////////////////////////// Vehicle //////////////////////////////////////////////////////////////////////////////



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



    #[Route('/getvehicle', name: 'getVehicles', methods: ['GET'])]
    #[IsGranted('ROLE_DRIVER')]
    public function getVehicle(SerializerInterface $serializer): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $vehicles =  $user->getVehicles();
        $jsonContent = $serializer->serialize($vehicles, 'json', ['groups' => ['vehicle:read']]);
        return new JsonResponse($jsonContent, JsonResponse::HTTP_OK, [], true);
    }


    ///////////////////////////////////////////////////////////////////// Reservation //////////////////////////////////////////////////////////////////////////////

    #[Route('/addreservation', name: 'add_reservation', methods: ['POST'])]
    public function addReservation(#[MapRequestPayload]  ReservationDto $reservationDto, ReservationFactory $reservationFactory): JsonResponse
    {
        $reservation = $reservationFactory->createFromDto($reservationDto);
        $this->em->persist($reservation);
        $this->em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    ///////////////////////////////////////////////////////////////////// USER //////////////////////////////////////////////////////////////////////////////


    #[Route('/getuser', name: 'get_user', methods: ['GET'])]
    public function getUserDetail(SerializerInterface $serializer): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $jsonContent = $serializer->serialize($user, 'json', ['groups' => ['user:read']]);
        return new JsonResponse($jsonContent, JsonResponse::HTTP_OK, [], true);
    }


    /////////////////////////////////////////////////////////////////// Itinerary //////////////////////////////////////////////////////////////////////////////////



    #[Route('/additinerary', name: 'add_itinerary', methods: ['POST'])]
    #[IsGranted('ROLE_DRIVER')]
    public function addItinerary(#[MapRequestPayload] ItineraryDto $itineraryDto, ItineraryFactory $itineraryFactory): JsonResponse
    {
        $itinerary = $itineraryFactory->createFromDto($itineraryDto);
        $this->em->persist($itinerary);
        $this->em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }



    #[Route('/getitineraries', name: 'get_itineraries', methods: ["GET"])]
    #[IsGranted('ROLE_DRIVER')]
    public function getItineraries(ItineraryRepository $itineraryRepository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $itineraries = $itineraryRepository->findByDriver($user);
        return $this->json($itineraries, 200, [], ['groups' => 'driver.itinerary.read']);
    }

    #[Route('/setitineraries/{id}', name: 'set_itineraries', methods: ["PATCH", "DELETE"], requirements: ['id' => Requirement::POSITIVE_INT])]
    #[IsGranted('ROLE_DRIVER')]
    public function setItinerary(Request $request, Itinerary $itinerary): JsonResponse
    {
        // Gérer la requête PATCH pour démarrer ou terminer un itinéraire
        if ($request->isMethod('PATCH')) {
            if ($itinerary->isCancelled() || $itinerary->isFinished()) {
                return $this->json(['message' => 'L\'itinéraire ne peut pas être mis à jour.'], JsonResponse::HTTP_BAD_REQUEST);
            }


            if ($itinerary->isStarted()) {
                $itinerary->setIsFinished(true);
            } else {
                $itinerary->setIsStarted(true);
            }

            $this->em->flush();
            return $this->json($itinerary, JsonResponse::HTTP_OK, [], ['groups' => 'driver.itinerary.read']);
        }

        // Gérer la requête DELETE pour annuler un itinéraire
        if ($request->isMethod('DELETE')) {
            if ($itinerary->isCancelled()) {
                return $this->json(['message' => 'L\'itinéraire est déjà annulé.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $itinerary->setIsCancelled(true);
            $this->em->flush();
            return $this->json($itinerary, JsonResponse::HTTP_OK, [], ['groups' => 'driver.itinerary.read']);
        }

        // Gérer le cas où la méthode n'est ni PATCH ni DELETE
        return $this->json(['message' => 'Méthode non supportée.'], JsonResponse::HTTP_METHOD_NOT_ALLOWED);
    }
}
