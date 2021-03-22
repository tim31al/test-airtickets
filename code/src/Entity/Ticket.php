<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
{
    const STATUS_FREE = 'free';
    const STATUS_BOOKED = 'booked';
    const STATUS_SOLD = 'sold';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $flightId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $passengerId;

    /**
     * @ORM\Column(type="smallint")
     */
    private $seat;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateOfSale;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFlightId(): ?int
    {
        return $this->flightId;
    }

    public function setFlightId(int $flightId): self
    {
        $this->flightId = $flightId;

        return $this;
    }

    public function getPassengerId(): ?int
    {
        return $this->passengerId;
    }

    public function setPassengerId(int $passengerId): self
    {
        $this->passengerId = $passengerId;

        return $this;
    }

    public function unsetPassenger(): self
    {
        $this->passengerId = null;
        return $this;
    }

    public function getSeat(): ?int
    {
        return $this->seat;
    }

    public function setSeat(int $seat): self
    {
        $this->seat = $seat;

        return $this;
    }

    public function getDateOfSale(): ?\DateTimeInterface
    {
        return $this->dateOfSale;
    }

    public function setDateOfSale(?\DateTimeInterface $dateOfSale): self
    {
        $this->dateOfSale = $dateOfSale;

        return $this;
    }

    public function unsetDateOfSale(): self
    {
        $this->dateOfSale = null;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'flight_id' => $this->getFlightId(),
            'passenger_id' => $this->getPassengerId(),
            'seat' => $this->getSeat(),
            'date_of_sale' => $this->getDateOfSale() ?
                $this->getDateOfSale()->format('Y-m-d H:m:s') : null,
            'status' => $this->getStatus(),
        ];
    }

    public function toPrint(): array
    {
        return [
            'id' => $this->getId(),
            'seat' => $this->getSeat(),
            'date_of_sale' => $this->getDateOfSale() ?
                $this->getDateOfSale()->format('Y-m-d H:m:s') : null,
            'status' => $this->getStatus(),
        ];
    }
}
