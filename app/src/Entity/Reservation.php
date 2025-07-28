<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $dateReservation = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seatsReserved = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $clientId = null;

    #[ORM\Column]
    private ?bool $isCancelled = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Itinerary $itinerary = null;


    public function __construct()
    {
        $this->dateReservation = new \DateTimeImmutable();
        $this->status = 'pending';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation(): ?\DateTimeImmutable
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTimeImmutable $dateReservation): static
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    public function getStatus(): ?string
    {

        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSeatsReserved(): ?int
    {
        return $this->seatsReserved;
    }

    public function setSeatsReserved(int $seatsReserved): static
    {
        $this->seatsReserved = $seatsReserved;

        return $this;
    }

    public function getClientId(): ?User
    {
        return $this->clientId;
    }

    public function setClientId(?User $clientId): static
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function isCancelled(): ?bool
    {
        return $this->isCancelled;
    }

    public function setIsCancelled(bool $isCancelled): static
    {
        $this->isCancelled = $isCancelled;

        return $this;
    }

    public function getItinerary(): ?itinerary
    {
        return $this->itinerary;
    }

    public function setItinerary(?itinerary $itinerary): static
    {
        $this->itinerary = $itinerary;

        return $this;
    }
}
