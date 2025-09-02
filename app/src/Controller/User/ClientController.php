<?php

namespace App\Controller\User;

use App\Entity\Itinerary;
use App\Entity\User;
use App\Entity\UserImage;
use App\Factory\CommentFactory;
use App\Model\VehicleDto;
use App\Model\ItineraryDto;
use App\Model\ReservationDto;
use App\Factory\VehicleFactory;
use App\Factory\ItineraryFactory;
use App\Factory\ReservationFactory;
use App\Model\CommentDTO;
use App\Repository\ItineraryRepository;
use App\Repository\ReservationRepository;
use App\Service\PaymentService;
use App\Service\SendStatusItinerary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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





    #[Route('/addImg', name: 'add_img', methods: ['POST'])]
    public function addImg(
        #[MapUploadedFile([
            new Assert\File(mimeTypes: ['image/jpeg', 'image/webp']),
            new Assert\Image(maxWidth: 3840, maxHeight: 2160),
        ])]
        UploadedFile $picture
    ): JsonResponse {
        // 1. On vérifie qu’un fichier a bien été uploadé
        if (null === $picture) {
            return $this->json(
                ['message' => 'Aucune image trouvée.'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        // 2. Récupération de l’utilisateur authentifié
        /** @var User $user */
        $user = $this->getUser();

        // 3. Récupération ou création de l’entité UserImage
        $userImage = $user->getUserImage();
        if (null === $userImage) {
            $userImage = new UserImage();
            $user->setUserImage($userImage);
        }

        // 4. Injection du nouveau fichier : 
        //    VichUploaderBundle va gérer le renommage,
        //    le déplacement et le remplacement automatique.
        $userImage->setImageFile($picture);

        // 5. Persistance et enregistrement en base de données
        $this->em->persist($userImage);
        $this->em->flush();

        // 6. Retour de l’URL publique de l’image
        return $this->json([
            'imageUrl' => '/uploads/users/' . $userImage->getImageFileName(),
        ], JsonResponse::HTTP_CREATED);
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
    public function setItinerary(Request $request, Itinerary $itinerary, SendStatusItinerary $sendStatusItinerary): JsonResponse
    {
        if ($request->isMethod('PATCH')) {
            if ($itinerary->isCancelled() || $itinerary->isFinished()) {
                return $this->json(['message' => 'L\'itinéraire ne peut pas être mis à jour.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            if ($itinerary->isStarted()) {
                $sendStatusItinerary->sendStatusItinerary(
                    $itinerary,
                    SendStatusItinerary::FINISHED
                );
                $itinerary->setIsFinished(true);
            } else {
                $sendStatusItinerary->sendStatusItinerary(
                    $itinerary,
                    SendStatusItinerary::STARTED
                );
                $itinerary->setIsStarted(true);
            }

            $this->em->flush();
            return $this->json($itinerary, JsonResponse::HTTP_OK, [], ['groups' => 'driver.itinerary.read']);
        }

        if ($request->isMethod('DELETE')) {
            if ($itinerary->isCancelled()) {
                return $this->json(['message' => 'L\'itinéraire est déjà annulé.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $sendStatusItinerary->sendStatusItinerary(
                $itinerary,
                SendStatusItinerary::CANCELLED
            );
            $itinerary->setIsCancelled(true);
            $this->em->flush();
            return $this->json($itinerary, JsonResponse::HTTP_OK, [], ['groups' => 'driver.itinerary.read']);
        }

        return $this->json(['message' => 'Méthode non supportée.'], JsonResponse::HTTP_METHOD_NOT_ALLOWED);
    }






    ///////////////////////////////////////////// COMMENT /////////////////////////////////////////////////



    #[Route('/addcomment', name: 'add_comment', methods: ['POST'])]
    public function addComment(#[MapRequestPayload] CommentDTO $commentdto, CommentFactory $commentFactory, PaymentService $paymentService): JsonResponse
    {

        /** @var User $user */
        $user = $this->getUser();


        $comment = $commentFactory->createFromDto($commentdto, $user);
        $comment->setUser($user);

        if ($comment->isApproved()) {
            $paymentService->hundlePayment($user, $comment);
        }

        $this->em->persist($comment);
        $this->em->flush();
        return new JsonResponse([
            'message' => 'Commentaire ajouté avec succès.',
            'id' => $comment->getId(),
        ], JsonResponse::HTTP_CREATED);
    }



    /////////////////////////////////////////////////// Reservation ////////////////////////////////////////////////////////////////////////



    #[Route('/getReservation', name: 'get_reservation', methods: ['GET'])]
    public function getReservation(ReservationRepository $reservationRepo): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $reservations = $reservationRepo->findby(['clientId' => $user->getId()]);
        return $this->json($reservations, 200, [], ['groups' => 'reservation:read']);
    }
}
