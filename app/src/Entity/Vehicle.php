<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $licence_plate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $registration_date = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $seats_available = null;

    #[ORM\Column]
    private ?bool $isSmocking_alowed = null;

    #[ORM\Column]
    private ?bool $isPets_alowed = null;

    #[ORM\Column(length: 50)]
    private ?string $model = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $driver = null;

    #[ORM\Column]
    private ?bool $isGreen = null;

    /**
     * @var Collection<int, Itinerary>
     */
    #[ORM\OneToMany(targetEntity: Itinerary::class, mappedBy: 'vehicule', orphanRemoval: true)]
    private Collection $itineraries;

    public function __construct()
    {
        $this->itineraries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLicencePlate(): ?string
    {
        return $this->licence_plate;
    }

    public function setLicencePlate(string $licence_plate): static
    {
        $this->licence_plate = $licence_plate;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeImmutable
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(\DateTimeImmutable $registration_date): static
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    public function getSeatsAvailable(): ?int
    {
        return $this->seats_available;
    }

    public function setSeatsAvailable(int $seats_available): static
    {
        $this->seats_available = $seats_available;

        return $this;
    }

    public function isSmockingAlowed(): ?bool
    {
        return $this->isSmocking_alowed;
    }

    public function setIsSmockingAlowed(bool $isSmocking_alowed): static
    {
        $this->isSmocking_alowed = $isSmocking_alowed;

        return $this;
    }

    public function isPetsAlowed(): ?bool
    {
        return $this->isPets_alowed;
    }

    public function setIsPetsAlowed(bool $isPets_alowed): static
    {
        $this->isPets_alowed = $isPets_alowed;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function isGreen(): ?bool
    {
        return $this->isGreen;
    }

    public function setIsGreen(bool $isGreen): static
    {
        $this->isGreen = $isGreen;

        return $this;
    }

    /**
     * @return Collection<int, Itinerary>
     */
    public function getItineraries(): Collection
    {
        return $this->itineraries;
    }

    public function addItinerary(Itinerary $itinerary): static
    {
        if (!$this->itineraries->contains($itinerary)) {
            $this->itineraries->add($itinerary);
            $itinerary->setVehicule($this);
        }

        return $this;
    }

    public function removeItinerary(Itinerary $itinerary): static
    {
        if ($this->itineraries->removeElement($itinerary)) {
            // set the owning side to null (unless already changed)
            if ($itinerary->getVehicule() === $this) {
                $itinerary->setVehicule(null);
            }
        }

        return $this;
    }
}
