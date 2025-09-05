<?php

namespace App\Controller\Admin;



use App\Document\UserAvis;
use App\Factory\UserFactory;

use App\Model\EditCommentDTO;
use App\Model\RequestUserDto;

use App\Service\PaymentService;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\ItineraryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/admin')]
final class AdministrationController extends AbstractController
{


    public function __construct(
        private EntityManagerInterface $em,

    ) {}

    /////////////////////////////////////////////////////////////////// MANAGER CONTROLLERS ///////////////////////////////////////////////////////////
    /**
     * @params CommentRepository
     * @return Json
     */

    #[Route('/avis', name: 'get_comments', methods: ['GET'])]
    public function getComments(CommentRepository $commentRepository): JsonResponse
    {
        $comments = $commentRepository->findby(['isApproved' => false, 'isArrived' => true]);
        return $this->json(
            $comments,
            JsonResponse::HTTP_OK,
            [],
            ['groups' => ['manager:reservation:read']]
        );
    }




    #[Route('/avispublic', name: 'get_avispublic', methods: ['GET'])]
    public function getavispublicnonValid(DocumentManager $documentManager): JsonResponse
    {
        $avis = $documentManager->getRepository(UserAvis::class)->findBy(['isValid' => false]);
        return $this->json($avis, JsonResponse::HTTP_OK);
    }






    #[Route('/avispublic/{id}', name: 'set_avispublic', methods: ['DELETE', 'PATCH'], requirements: ["id" => Requirement::MONGODB_ID])]
    public function setavispublicnonValid(UserAvis $userAvis, DocumentManager $documentManager, Request $request): JsonResponse
    {
        if (!$userAvis) {
            return $this->json(null, JsonResponse::HTTP_NOT_FOUND);
        }
        $methode = $request->getMethod();
        if ($methode === "DELETE") {
            $documentManager->remove($userAvis);
            $documentManager->flush();
            return $this->json(["message" => "Avis supprimé"], JsonResponse::HTTP_OK);
        }
        if ($methode === "PATCH") {
            $userAvis->setIsValid(true);
            $documentManager->flush();
            return $this->json(["message" => "Avis validé"], JsonResponse::HTTP_OK);
        }



        return $this->json(["message" => "this should not be arrived "], JsonResponse::HTTP_BAD_REQUEST);
    }


    #[Route('/avis', name: 'edit_comments', methods: ['PATCH'])]
    public function editComment(#[MapRequestPayload] EditCommentDTO $editCommentDTO, CommentRepository $commentRepository, UserRepository $userRepo, PaymentService $paymentService): JsonResponse
    {

        $comment = $commentRepository->find($editCommentDTO->commentId);
        if (!$comment) {
            return new JsonResponse(['error' => 'Commentaire introuvable'], 404);
        }
        if ($comment->isApproved()) {
            return $this->json(null, JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $userRepo->findOneBy(['email' => $editCommentDTO->userEmail]);
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur introuvable'], 404);
        }


        $paymentService->hundlePayment($user, $comment);
        $comment->setIsApproved(true);
        if (!$editCommentDTO->isValid) {
            $comment->setContent('cet avis a été annulé par l\'administration .');
        };

        $this->em->flush();
        return $this->json(['message' => 'validé'], JsonResponse::HTTP_OK);
    }


    //////////////////////////////////////////////////////////////---ADMIN CONTROLLES ---////////////////////////////////////////////////////////////////////


    #[Route('/adduser', name: 'add_employe', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function addUser(#[MapRequestPayload] RequestUserDto $userDto, UserFactory $userFactory): JsonResponse
    {

        $user = $userFactory->createFromDto($userDto);
        $user->setRoles(['ROLE_MANAGER']);
        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }




    #[Route('/getuser', name: 'get_AdminUser', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getUsers(#[MapQueryParameter(filter: \FILTER_VALIDATE_EMAIL)] string $email, UserRepository $repoUser): JsonResponse
    {

        $user =  $repoUser->findOneBy(['email' => $email]);
        if (!$user) {
            return $this->json([
                'message' => 'Aucun utilisateur trouvé avec cet email.'
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        return $this->json($user, JsonResponse::HTTP_ACCEPTED, [], ['groups' => 'admin:read:user']);
    }


    #[Route('/deleteuser', name: 'delete_AdminUser', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteUser(#[MapQueryParameter(filter: \FILTER_VALIDATE_EMAIL)] string $email, UserRepository $repoUser): JsonResponse
    {


        $user = $repoUser->findOneBy(['email' => $email]);
        if (!$user) {
            return $this->json([
                'message' => 'Aucun utilisateur trouvé avec cet email.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        if (in_array("ROLE_ADMIN", $user->getroles())) {
            return $this->json(
                ['message' => 'Impossible de supprimer un utilisateur avec le rôle ADMIN.'],
                JsonResponse::HTTP_FORBIDDEN

            );
        }
        $this->em->remove($user);
        $this->em->flush();
        return $this->json(null, JsonResponse::HTTP_OK);
    }





    #[Route('/getdataitineraries', name: 'get_itinerariesData', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getDataItineraries(ItineraryRepository $repoItinerary): JsonResponse
    {
        $stats = $repoItinerary->findItinerariesData();
        return $this->json($stats, JsonResponse::HTTP_OK);
    }




    #[Route('/getdataCredits', name: 'get_CountCredits', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getDataCredit(ReservationRepository $reporeservation): JsonResponse
    {
        $stats = $reporeservation->getDailyCredits();
        return $this->json($stats, JsonResponse::HTTP_OK);
    }
}
