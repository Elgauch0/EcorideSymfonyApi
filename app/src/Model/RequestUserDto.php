<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class RequestUserDto
{
    public function __construct(
        #[Assert\NotBlank(message: "L'adresse email est obligatoire.")]
        #[Assert\Email(message: "L'adresse email n'est pas valide.")]
        #[Assert\Length(max: 180, maxMessage: "L'adresse email ne peut pas dépasser {{ limit }} caractères.")]
        #[Assert\Regex(
            pattern: '/^[^<>]*$/',
            message: 'l\'email ne doit pas contenir de balises HTML ou de scripts.'
        )]
        public ?string $email = null,

        #[Assert\NotBlank(message: "Le mot de passe est obligatoire.")]
        #[Assert\Length(min: 8, minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères.")]
        public ?string $plainPassword = null,

        #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
        #[Assert\Length(min: 2, max: 50, minMessage: "Le prénom doit contenir au moins {{ limit }} caractères.", maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères.")]
        #[Assert\Regex(
            pattern: '/^[^<>]*$/',
            message: 'le prénom ne doit pas contenir de balises HTML ou de scripts.'
        )]
        public ?string $firstName = null,

        #[Assert\NotBlank(message: "Le nom de famille est obligatoire.")]
        #[Assert\Length(min: 2, max: 50, minMessage: "Le nom de famille doit contenir au moins {{ limit }} caractères.", maxMessage: "Le nom de famille ne peut pas dépasser {{ limit }} caractères.")]
        #[Assert\Regex(
            pattern: '/^[^<>]*$/',
            message: 'lastName ne doit pas contenir de balises HTML ou de scripts.'
        )]
        public ?string $lastName = null,

        #[Assert\NotBlank(message: "L'adresse est obligatoire.")]
        #[Assert\Length(min: 5, max: 100, minMessage: "L'adresse doit contenir au moins {{ limit }} caractères.", maxMessage: "L'adresse ne peut pas dépasser {{ limit }} caractères.")]
        #[Assert\Regex(
            pattern: '/^[^<>]*$/',
            message: 'l\'adress ne doit pas contenir de balises HTML ou de scripts.'
        )]
        public ?string $adress = null,
    ) {}
}
