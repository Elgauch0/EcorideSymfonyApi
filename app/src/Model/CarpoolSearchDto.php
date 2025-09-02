<?php

// src/Model/CarpoolSearchDto.php

namespace App\Model;


use Symfony\Component\Validator\Constraints as Assert;

class CarpoolSearchDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le lieu de départ est requis.')]
        #[Assert\Type('string', message: 'Le départ doit être une chaîne de caractères.')]
        #[Assert\Length(min: 2, max: 255, minMessage: 'Le départ doit contenir au moins {{ limit }} caractères.')]
        #[Assert\Regex(
            pattern: '/^[\p{L}0-9 .,!?-]+$/u',
            message: 'Le contenu ne peut contenir que des lettres, chiffres et ponctuation basique.'
        )]
        public ?string $depart = null,

        #[Assert\NotBlank(message: 'La destination est requise.')]
        #[Assert\Type('string', message: 'La destination doit être une chaîne de caractères.')]
        #[Assert\Length(min: 2, max: 255, minMessage: 'La destination doit contenir au moins {{ limit }} caractères.')]
        #[Assert\Regex(
            pattern: '/^[\p{L}0-9 .,!?-]+$/u',
            message: 'Le contenu ne peut contenir que des lettres, chiffres et ponctuation basique.'
        )]
        public ?string $destination = null,

        #[Assert\NotBlank(message: 'La date est requise.')]
        #[Assert\Type('string', message: 'La date doit être une chaîne de caractères.')]
        #[Assert\Date(message: 'Le format de la date est invalide (attendu : YYYY-MM-DD).')]
        public ?string $date = null
    ) {}
}
