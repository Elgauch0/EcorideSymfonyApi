<?php

// src/Model/VehicleDto.php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class VehicleDto
{
    public function __construct(

        #[Assert\NotBlank(message: 'La plaque d’immatriculation est requise.')]
        #[Assert\Type('string', message: 'La plaque doit être une chaîne de caractères.')]
        #[Assert\Length(min: 6, max: 20, minMessage: 'La plaque doit contenir au moins {{ limit }} caractères.')]
        #[Assert\Regex(
            pattern: '/^[^<>]*$/',
            message: 'La licence Plate ne doit pas contenir de balises HTML ou de scripts.'
        )]
        public string $licencePlate,

        #[Assert\NotBlank(message: 'La date d’enregistrement est requise.')]
        #[Assert\Date(message: 'Le format de la date est invalide (attendu : YYYY-MM-DD).')]
        public string $registrationDate,

        #[Assert\NotNull(message: 'Le nombre de sièges est requis.')]
        #[Assert\Type('integer', message: 'Le nombre de sièges doit être un entier.')]
        #[Assert\Positive(message: 'Le nombre de sièges doit être supérieur à zéro.')]
        public int $seatsAvailable,

        #[Assert\Type('bool', message: 'Le champ fumeur doit être un booléen.')]
        public bool $isSmockingAlowed = false,

        #[Assert\Type('bool', message: 'Le champ animaux autorisés doit être un booléen.')]
        public bool $isPetsAlowed = false,

        #[Assert\NotBlank(message: 'Le modèle du véhicule est requis.')]
        #[Assert\Type('string', message: 'Le modèle doit être une chaîne de caractères.')]
        #[Assert\Length(min: 2, max: 255)]
        #[Assert\Regex(
            pattern: '/^[^<>]*$/',
            message: 'Le model ne doit pas contenir de balises HTML ou de scripts.'
        )]
        public string $model,

        #[Assert\Type('bool', message: 'Le champ véhicule écologique doit être un booléen.')]
        public bool $isGreen = false
    ) {}
}
