<?php

namespace App\Controller;

use App\Model\CarpoolSearchDto;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTime;

#[Route('/api/itinerary')]
final class ItineraryController extends AbstractController
{
    #[Route('/search', name: 'app_itinerary', methods: ['POST'], format: 'json')]
    public function index(
        #[MapRequestPayload] CarpoolSearchDto $searchDto
    ): JsonResponse {

        $depart      = trim($searchDto->depart);
        $destination = trim($searchDto->destination);
        $dateObj     = trim($searchDto->date);

        return new JsonResponse([
            'depart'      => $depart,
            'destination' => $destination,
            'date'        => $dateObj
        ]);
    }
}
