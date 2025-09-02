<?php

// src/Model/EmailDTO.php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;


class EmailDTO
{








    public function __construct(

        #[Assert\NotBlank(message: "L'email est obligatoire.")]
        #[Assert\Regex(
            pattern: '/^[\p{L}0-9 .,!?-@]+$/u',
            message: 'Le contenu ne peut contenir que des lettres, chiffres et ponctuation basique.'
        )]
        #[Assert\Email(message: 'L’email doit être une adresse email valide.')]
        public  string $email,



        #[Assert\NotBlank(message: "Le contenu est obligatoire.")]
        #[Assert\Type(
            type: 'string',
            message: 'Le contenu doit être une chaîne de caractères.'
        )]
        #[Assert\Length(
            max: 255,
            maxMessage: 'Le contenu ne peut pas dépasser {{ limit }} caractères.'
        )]
        #[Assert\Regex(
            pattern: '/^[\p{L}0-9 .,!?-@]+$/u',
            message: 'Le contenu ne peut contenir que des lettres, chiffres et ponctuation basique.'
        )]
        public string $content
    ) {}
}
