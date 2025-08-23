<?php
// src/Model/ItineraryDto.php
namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ItineraryDto
{
    public function __construct(
        #[Assert\NotNull(message: 'L’identifiant du véhicule est requis.')]
        #[Assert\Type('integer', message: 'L’identifiant du véhicule doit être un entier.')]
        public int $vehiculeId,

        #[Assert\NotNull(message: 'La durée est requise.')]
        #[Assert\Type('integer', message: 'La durée doit être un entier.')]
        #[Assert\Positive(message: 'La durée doit être supérieure à zéro.')]
        public int $duration,

        #[Assert\NotNull(message: 'Le prix est requis.')]
        #[Assert\Type('numeric', message: 'Le prix doit être un nombre.')]
        #[Assert\GreaterThanOrEqual(0, message: 'Le prix ne peut pas être négatif.')]
        public float $price,

        #[Assert\NotBlank(message: 'La date et l’heure du trajet sont requises.')]
        #[Assert\DateTime(message: 'Le format de la date est invalide (attendu : YYYY-MM-DD HH:MM:SS).')]
        public string $datetime,

        #[Assert\Type('integer', message: 'Le nombre de places doit être un entier.')]
        #[Assert\Positive(message: 'Le nombre de places doit être supérieur à zéro.')]
        public ?int $places = null,

        #[Assert\NotBlank(message: 'La ville de départ est requise.')]
        #[Assert\Type('string', message: 'La ville de départ doit être une chaîne de caractères.')]
        #[Assert\Length(min: 2, max: 255)]
        public string $departureCity,

        #[Assert\NotBlank(message: 'La ville d’arrivée est requise.')]
        #[Assert\Type('string', message: 'La ville d’arrivée doit être une chaîne de caractères.')]
        #[Assert\Length(min: 2, max: 255)]
        public string $arrivalCity
    ) {}
}
