<?php

namespace App\Controller;


use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class TestController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function test(): JsonResponse
    {
        return new JsonResponse(['message' => "server is UP"]);
    }

    #[Route('/api/admin', name: 'admin_test', methods: ['GET'])]
    public function admin_test(): JsonResponse
    {
        return new JsonResponse(['message' => 'super info']);
    }


    #[Route('/api/guest', name: 'public_test', methods: ['GET'])]
    public function public_test(): JsonResponse
    {
        return new JsonResponse(['message' => 'data public']);
    }


    #[Route('/api/user', name: 'user_test', methods: ['GET'])]
    public function user_test(): JsonResponse
    {
        return new JsonResponse(['message' => 'user info']);
    }
}
