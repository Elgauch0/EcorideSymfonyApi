<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ItineraryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ItineraryRepository::class)]
class Itinerary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["itinerary:read"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(["itinerary:read"])]
    private ?int $duration = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(["itinerary:read"])]
    private ?int $price = null;

    #[ORM\Column]
    #[Groups(["itinerary:read"])]
    private ?\DateTimeImmutable $datetime = null;

    #[ORM\Column]
    private ?bool $isStarted = null;

    #[ORM\Column]
    private ?bool $isFinished = null;

    #[ORM\Column]
    private ?bool $isCancelled = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(["itinerary:read"])]
    private ?int $places = null;

    #[ORM\ManyToOne(inversedBy: 'itineraries')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["itinerary:read"])]
    private ?Vehicle $vehicule = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'itinerary', orphanRemoval: true)]
    private Collection $reservations;

    #[ORM\Column(length: 50)]
    private ?string $departureCity = null;

    #[ORM\Column(length: 50)]
    private ?string $arrivalCity = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDatetime(): ?\DateTimeImmutable
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeImmutable $datetime): static
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function isStarted(): ?bool
    {
        return $this->isStarted;
    }

    public function setIsStarted(bool $isStarted): static
    {
        $this->isStarted = $isStarted;

        return $this;
    }

    public function isFinished(): ?bool
    {
        return $this->isFinished;
    }

    public function setIsFinished(bool $isFinished): static
    {
        $this->isFinished = $isFinished;

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

    public function getPlaces(): ?int
    {
        return $this->places;
    }

    public function setPlaces(int $places): static
    {
        $this->places = $places;

        return $this;
    }

    public function getVehicule(): ?vehicle
    {
        return $this->vehicule;
    }

    public function setVehicule(?vehicle $vehicule): static
    {
        $this->vehicule = $vehicule;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setItinerary($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getItinerary() === $this) {
                $reservation->setItinerary(null);
            }
        }

        return $this;
    }

    public function getDepartureCity(): ?string
    {
        return $this->departureCity;
    }

    public function setDepartureCity(string $departureCity): static
    {
        $this->departureCity = $departureCity;

        return $this;
    }

    public function getArrivalCity(): ?string
    {
        return $this->arrivalCity;
    }

    public function setArrivalCity(string $arrivalCity): static
    {
        $this->arrivalCity = $arrivalCity;

        return $this;
    }
}
