<?php

namespace App\Service;

use App\Entity\Itinerary;
use App\Service\MailerService;






class SendStatusItinerary
{




    public const STARTED = 'Bonjour client, votre itinéraire a commencé.';
    public const FINISHED = 'Bonjour client, votre itinéraire est terminé ! Veuillez valider votre trajet SVP.';
    public const CANCELLED = 'Bonjour client, votre itinéraire est annulé.';




    public  function __construct(public MailerService $mailerService) {}



    public function sendStatusItinerary(Itinerary $itinerary, string $content): void
    {


        $reservations = $itinerary->getReservations();

        foreach ($reservations as $reservation) {
            $email = $reservation->getClientId()->getEmail();
            $this->mailerService->sendtoUser($email, $content);
        };
    }
}
