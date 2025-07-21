<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function test(): JsonResponse
    {
        return new JsonResponse(' El-kaouri server is Up');
    }



    #[Route("/api/users")]
    public function getUsers(UserRepository $repo): JsonResponse
    {

        $users = $repo->findAll();
        return new JsonResponse($users);
    }
}
