<?php
// src/Document/User.php
namespace App\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Serializer\Attribute\Groups;


#[ODM\Document]
class UserAvis
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: 'string')]
    #[Groups(['AvisPublic:read'])]
    private string $nickname;

    #[ODM\Field(type: 'string')]
    #[Groups(['AvisPublic:read'])]
    private string $review;


    #[ODM\Field(type: 'bool')]
    private bool $isValid;

    public function __construct(string $nickname, string $review)
    {
        $this->nickname = $nickname;
        $this->setReview($review);
        $this->isValid = false;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getReview(): string
    {
        return $this->review;
    }

    public function setReview(string $review): void
    {
        // On limite à 255 caractères
        $this->review = mb_substr($review, 0, 255);
    }
    public function setIsValid(bool $isValid): void
    {
        $this->isValid = $isValid;
    }
    public function getIsValid(): bool
    {
        return $this->isValid;
    }
}
