<?php

namespace App\Controller\Guest;

use App\Model\CarpoolSearchDto;
use App\Repository\ItineraryRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api/guest')]
final class ItineraryController extends AbstractController
{

    public function __construct(
        private ItineraryRepository $itineraryRepository,
        private SerializerInterface $serializer
    ) {}

    #[Route('/search', name: 'app_itinerary', methods: ['POST'])]
    public function index(
        #[MapRequestPayload] CarpoolSearchDto $searchDto
    ): JsonResponse {
        $itineraries = $this->itineraryRepository->findBySearchCriteria($searchDto);
        if (empty($itineraries)) {
            return $this->json($searchDto, JsonResponse::HTTP_OK);
        }
        $jsonContent = $this->serializer->serialize($itineraries, 'json', ['groups' => ['itinerary:read']]);

        return new JsonResponse(
            $jsonContent,
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
