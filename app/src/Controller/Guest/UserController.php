<?php

namespace App\Controller\Guest;

use App\Entity\User;
use App\Model\RequestUserDto;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;




#[Route('/api/guest')]
final class UserController extends AbstractController
{

    public function __construct(
        private SerializerInterface $serializer,
        private UserRepository $repository,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $em

    ) {}

    #[Route('/adduser', name: 'add_user', methods: ['POST'])]
    public function addUser(#[MapRequestPayload] RequestUserDto $userDto): JsonResponse
    {

        $user = new User();
        $user->setFirstname($userDto->firstName);
        $user->setLastname($userDto->lastName);
        $user->setAdress($userDto->adress);
        $user->setEmail($userDto->email);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $userDto->plainPassword);
        $user->setPassword($hashedPassword);

        $currentUser = $this->getUser();
        if ($currentUser && in_array('ROLE_ADMIN', $currentUser->getRoles())) {
            $user->setRoles($userDto->roles);
        } else {
            $user->setRoles(['ROLE_USER']);
        }
        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }
}
