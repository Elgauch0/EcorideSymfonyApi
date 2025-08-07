<?php
// src/Model/RequestUser.php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class RequestUserDto
{
    // --- Constantes pour les rôles disponibles ---
    private const AVAILABLE_ROLES = [
        'ROLE_ADMIN',
        'ROLE_MANAGER',
        'ROLE_DRIVER',
        'ROLE_USER'
    ];
    // --- Fin des constantes ---

    public function __construct(
        // --- Email ---
        #[Assert\NotBlank(message: "L'adresse email est obligatoire.", groups: ['registration', 'user_update'])]
        #[Assert\Email(message: "L'adresse email n'est pas valide.")]
        #[Assert\Length(max: 180, maxMessage: "L'adresse email ne peut pas dépasser {{ limit }} caractères.")]
        #[Assert\NoSuspiciousCharacters]
        public ?string $email = null,

        // --- Mot de passe (pour l'inscription ou changement de mot de passe) ---
        #[Assert\NotBlank(message: "Le mot de passe est obligatoire.", groups: ['registration', 'password_update'])]
        #[Assert\Length(min: 8, minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères.", groups: ['registration', 'password_update'])]
        #[Assert\Regex(
            pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            message: "Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.",
            groups: ['registration', 'password_update']
        )]
        public ?string $plainPassword = null,

        // --- Prénom (firstName) ---
        #[Assert\NotBlank(message: "Le prénom est obligatoire.", groups: ['registration', 'user_update'])]
        #[Assert\Length(min: 2, max: 50, minMessage: "Le prénom doit contenir au moins {{ limit }} caractères.", maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères.")]
        #[Assert\NoSuspiciousCharacters]
        public ?string $firstName = null,

        // --- Nom de famille (lastName) ---
        #[Assert\NotBlank(message: "Le nom de famille est obligatoire.", groups: ['registration', 'user_update'])]
        #[Assert\Length(min: 2, max: 50, minMessage: "Le nom de famille doit contenir au moins {{ limit }} caractères.", maxMessage: "Le nom de famille ne peut pas dépasser {{ limit }} caractères.")]
        #[Assert\NoSuspiciousCharacters]
        public ?string $lastName = null,

        // --- Adresse (adress) ---
        #[Assert\NotBlank(message: "L'adresse est obligatoire.", groups: ['registration', 'user_update'])]
        #[Assert\Length(min: 5, max: 100, minMessage: "L'adresse doit contenir au moins {{ limit }} caractères.", maxMessage: "L'adresse ne peut pas dépasser {{ limit }} caractères.")]
        #[Assert\NoSuspiciousCharacters]
        public ?string $adress = null,

        // --- Rôle (single string) ---
        #[Assert\All([
            new Assert\Choice(
                choices: self::AVAILABLE_ROLES,
                message: 'Le rôle "{{ value }}" n\'est pas valide.'
            )
        ])]
        public ?array $roles = ['ROLE_USER']
    ) {}
}
