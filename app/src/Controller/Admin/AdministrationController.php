<?php

namespace App\Controller\Admin;


use App\Model\EditCommentDTO;
use App\Repository\CommentRepository;
use App\Repository\ItineraryRepository;
use App\Repository\UserRepository;
use App\Service\PaymentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}
