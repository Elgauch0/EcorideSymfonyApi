<?php

namespace App\Controller\Guest;


use App\Factory\UserFactory;
use App\Model\EmailDTO;
use App\Model\RequestUserDto;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
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
}
