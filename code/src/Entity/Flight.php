<?php

namespace App\Entity;

use App\Repository\FlightRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FlightRepository::class)
 */
class Flight
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $departure;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $arrival;

    /**
     * @ORM\Column(type="datetime")
     */
    private $departureTime;

    /**
     * @ORM\Column(type="smallint")
     */
    private $seatsNumber;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOnSale;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getDeparture(): ?string
    {
        return $this->departure;
    }

    public function setDeparture(string $departure): self
    {
        $this->departure = $departure;

        return $this;
    }

    public function getArrival(): ?string
    {
        return $this->arrival;
    }

    public function setArrival(string $arrival): self
    {
        $this->arrival = $arrival;

        return $this;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departureTime;
    }

    public function setDepartureTime(\DateTimeInterface $departureTime): self
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    public function getSeatsNumber(): ?int
    {
        return $this->seatsNumber;
    }

    public function setSeatsNumber(int $seatsNumber): self
    {
        $this->seatsNumber = $seatsNumber;

        return $this;
    }

    public function getIsOnSale(): ?bool
    {
        return $this->isOnSale;
    }

    public function setIsOnSale(bool $isOnSale): self
    {
        $this->isOnSale = $isOnSale;

        return $this;
    }


    public function toPrint(): array
    {
        return [
            'id' => $this->getId(),
            'company' => $this->getCompany(),
            'departure' => $this->getDeparture(),
            'arrival' => $this->getArrival(),
            'departure_time' => $this->getDepartureTime()->format('Y-m-d H:m:s'),
        ];
    }
}
