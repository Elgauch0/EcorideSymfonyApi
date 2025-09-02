<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class CommentDTO
{
    public function __construct(
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
        public ?string $content = null,

        #[Assert\NotNull(
            message: 'L’identifiant d’itinéraire est requis.'
        )]
        #[Assert\Type(
            type: 'integer',
            message: 'L’identifiant d’itinéraire doit être un entier.'
        )]
        #[Assert\Positive(
            message: 'L’identifiant d’itinéraire doit être strictement positif.'
        )]
        public int $id_itinerary,



        #[Assert\Type(
            type: 'integer',
            message: 'La note doit être un entier.'
        )]
        #[Assert\Range(
            min: 1,
            max: 5,
            notInRangeMessage: 'La note doit être comprise entre {{ min }} et {{ max }}.'
        )]
        public int $note = 5,

        #[Assert\Type(
            type: 'bool',
            message: 'Le statut isArrived doit être un booléen.'
        )]
        public bool $isArrived = true,

        #[Assert\Type(
            type: 'bool',
            message: 'Le statut isApproved doit être un booléen.'
        )]
        public bool $isApproved = false,
    ) {}
}
