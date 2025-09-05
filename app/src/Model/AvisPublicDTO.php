<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;


class AvisPublicDTO
{




    public function __construct(

        #[Assert\Type(
            type: 'string',
            message: 'Le contenu doit être une chaîne de caractères.'
        )]
        #[Assert\Length(
            max: 50,
            maxMessage: 'Le contenu ne peut pas dépasser {{ limit }} caractères.'
        )]
        #[Assert\Regex(
            pattern: '/^[\p{L}0-9 .,!?-]+$/u',
            message: 'Le contenu ne peut contenir que des lettres, chiffres et ponctuation basique.'
        )]
        public string  $nickname,


        #[Assert\Type(
            type: 'string',
            message: 'Le contenu doit être une chaîne de caractères.'
        )]
        #[Assert\Length(
            max: 255,
            maxMessage: 'Le contenu ne peut pas dépasser {{ limit }} caractères.'
        )]
        #[Assert\Regex(
            pattern: '/^[\p{L}0-9 .,!?-]+$/u',
            message: 'Le contenu ne peut contenir que des lettres, chiffres et ponctuation basique.'
        )]
        public string $review,




    ) {}
}
