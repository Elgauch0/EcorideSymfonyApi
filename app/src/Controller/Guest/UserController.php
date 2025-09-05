<?php

namespace App\Controller\Guest;

use App\Model\EmailDTO;
use App\Document\UserAvis;

use App\Factory\UserFactory;

use App\Model\AvisPublicDTO;
use App\Model\RequestUserDto;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api/guest')]
final class UserController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em

    ) {}

    #[Route('/adduser', name: 'add_user', methods: ['POST'])]
    public function addUser(#[MapRequestPayload] RequestUserDto $userDto, UserFactory $userFactory): JsonResponse
    {

        $user = $userFactory->createFromDto($userDto);
        $user->setRoles(['ROLE_USER']);
        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }


    #[Route('/email', name: 'get_Emails', methods: ['POST'])]
    public function getEmails(#[MapRequestPayload] EmailDTO $emailDTO, MailerService $mailerService): JsonResponse
    {

        $mailerService->sendToAdmin($emailDTO->email, $emailDTO->content);
        return $this->json(null, JsonResponse::HTTP_OK);
    }



    #[Route('/avis', name: 'public_Avis', methods: ['POST'])]
    public function avispublic(#[MapRequestPayload] AvisPublicDTO $avisPublicDTO, DocumentManager $documentManager): JsonResponse
    {
        $userAvis = new UserAvis($avisPublicDTO->nickname, $avisPublicDTO->review);

        $documentManager->persist($userAvis);
        $documentManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }




    #[Route('/avis', name: 'get_avis', methods: ['GET'])]
    public function getavispublic(DocumentManager $documentManager): JsonResponse
    {
        $avis = $documentManager->getRepository(UserAvis::class)->findBy(['isValid' => true]);
        return $this->json($avis, JsonResponse::HTTP_OK, [], ['groups' => 'AvisPublic:read']);
    }
}
