<?php

// src/Model/ReservationDto.php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ReservationDto
{
    public function __construct(
        #[Assert\NotNull(message: 'L’identifiant du client est requis.')]
        #[Assert\Type('integer', message: 'L’identifiant du client doit être un entier.')]
        #[Assert\Positive(message: 'L’identifiant du client doit être positif.')]
        public int $clientId,

        #[Assert\NotNull(message: 'L’identifiant du trajet (itinéraire) est requis.')]
        #[Assert\Type('integer', message: 'L’identifiant du trajet doit être un entier.')]
        #[Assert\Positive(message: 'L’identifiant du trajet doit être positif.')]
        public int $itineraryId,

        #[Assert\NotNull(message: 'Le nombre de sièges réservés est requis.')]
        #[Assert\Type('integer', message: 'Le nombre de sièges doit être un entier.')]
        #[Assert\Positive(message: 'Le nombre de sièges doit être supérieur à zéro.')]
        public int $seatsReserved,


    ) {}
}
