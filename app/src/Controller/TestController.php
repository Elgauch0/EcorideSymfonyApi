<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


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


    #[Route('/api/public', name: 'public_test', methods: ['GET'])]
    public function public_test(): JsonResponse
    {
        return new JsonResponse(['message' => 'data public']);
    }
}
