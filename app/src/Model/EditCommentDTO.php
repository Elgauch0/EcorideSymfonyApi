<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class EditCommentDTO
{
    public function __construct(
        // Identifiant du commentaire
        #[Assert\NotNull(message: 'L’identifiant du commentaire est requis.')]
        #[Assert\Type('integer', message: 'L’identifiant du commentaire doit être un entier.')]
        #[Assert\Positive(message: 'L’identifiant du commentaire doit être positif.')]
        public int $commentId,

        // Email de l’utilisateur
        #[Assert\NotBlank(message: 'L’email est requis.')]
        #[Assert\Email(message: 'L’email doit être une adresse email valide.')]
        #[Assert\Length(
            max: 180,
            maxMessage: 'L’email ne peut pas dépasser {{ limit }} caractères.'
        )]
        // Empêche toute balise HTML ou script (premier rempart anti-XSS)
        #[Assert\Regex(
            pattern: '/^[\p{L}0-9 .,!?-@]+$/u',
            message: 'Le contenu ne peut contenir que des lettres, chiffres et ponctuation basique.'
        )]
        public string $userEmail,

        // Statut de validation
        #[Assert\NotNull(message: 'Le statut de validation est requis.')]
        #[Assert\Type('bool', message: 'Le statut de validation doit être un booléen.')]
        public bool $isValid,

    ) {}
}
