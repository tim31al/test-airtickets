<?php

namespace App\Service;

use App\Entity\Ticket;
use App\Exceptions\FlightNotFoundException;
use App\Exceptions\FlightSalesBlocked;
use App\Exceptions\TicketBlockedException;
use App\Exceptions\TicketNotFoundException;
use App\Repository\FlightRepository;
use App\Repository\PassengerRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;

class TicketService
{
    private TicketRepository $ticketRepository;
    private FlightRepository $flightRepository;
    private PassengerRepository $passengerRepository;
    private EntityManagerInterface $entityManager;


    /**
     * TicketService constructor.
     * @param TicketRepository $ticketRepository
     * @param FlightRepository $flightRepository
     * @param PassengerRepository $passengerRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        TicketRepository $ticketRepository,
        FlightRepository $flightRepository,
        PassengerRepository $passengerRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->ticketRepository = $ticketRepository;
        $this->flightRepository = $flightRepository;
        $this->passengerRepository = $passengerRepository;
        $this->entityManager = $entityManager;
    }


    public function buyOrBook($ticketId, $passenger, $toBook = false): array
    {
        $passenger = $this->passengerRepository->findOrCreate($passenger);
        list($ticket, $flight) = $this->getTicketDataToSale($ticketId, $passenger, $toBook);

        $ticket->setPassengerId($passenger->getId());
        if ($toBook) {
            $ticket->setStatus(Ticket::STATUS_BOOKED);
        } else {
            $ticket->setStatus(Ticket::STATUS_SOLD);
        }

        $ticket->setDateOfSale(new \DateTime());

        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

        return [
            'flight' => $flight->toPrint(),
            'passenger' => $passenger->toPrint(),
            'ticket' => $ticket->toPrint(),
        ];
    }

    public function cancelResevation($ticketId): array
    {
        $ticket = $this->getTicketDataToCancel($ticketId);
        $ticket->setStatus(Ticket::STATUS_FREE);
        $ticket->unsetPassenger();
        $ticket->unsetDateOfSale();

        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

        return $ticket->toArray();
    }

    private function getTicketDataToCancel($ticketId): Ticket
    {
        $ticket = $this->ticketRepository->find((int)$ticketId);
        if (null === $ticket) {
            throw new TicketNotFoundException(sprintf('Ticket id:%s not found', $ticketId));
        }

        return $ticket;
    }


    private function getTicketDataToSale($ticketId, $passenger, $toBook): array
    {
        $ticket = $this->ticketRepository->find((int)$ticketId);
        if (null === $ticket) {
            throw new TicketNotFoundException(sprintf('Ticket id:%s not found', $ticketId));
        }

        if ($toBook && $ticket->getStatus() !== Ticket::STATUS_FREE) {
            throw new TicketBlockedException(sprintf('Ticket id:%s blocked', $ticket->getId()));
        }

        if (
            !$toBook &&
            $ticket->getStatus() !== Ticket::STATUS_FREE &&
            $ticket->getPassengerId() !== $passenger->getId()
        ) {
            throw new TicketBlockedException(sprintf('Ticket id:%s blocked', $ticket->getId()));
        }


        $flight = $this->flightRepository->find($ticket->getFlightId());
        if ($flight === null) {
            throw new FlightNotFoundException(sprintf('Flight id:%s not found', $ticket->getFlightId()));
        } else if (!$flight->getIsOnSale()) {
            throw new FlightSalesBlocked(sprintf('Flight id:%s sales blocked', $flight->getId()));
        }

        return [$ticket, $flight];
    }

}
