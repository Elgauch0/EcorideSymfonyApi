<?php

namespace App\Factory;

use App\Entity\User;
use App\Model\RequestUserDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class UserFactory
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
    ) {}




    public function createFromDto(RequestUserDto $userDto): User
    {
        $user = new User();
        $user->setFirstname($userDto->firstName);
        $user->setLastname($userDto->lastName);
        $user->setAdress($userDto->adress);
        $user->setEmail($userDto->email);
        $user->setCredits(200);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $userDto->plainPassword);
        $user->setPassword($hashedPassword);
        return $user;
    }
}
