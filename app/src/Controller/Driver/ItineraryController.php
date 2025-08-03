<?php

namespace App\Controller\Driver;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ItineraryController extends AbstractController
{
    #[Route('/itinerary', name: 'app_itinerary')]
    public function index(): JsonResponse
    {
        return new JsonResponse(['message' => 'itinerary']);
    }
}
