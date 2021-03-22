<?php

namespace App\Service;

use App\Entity\Flight;
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
        $ticket = $toBook ?
            $this->getTicketDataToBook($ticketId) :
            $this->getTicketDataToSale($ticketId, $passenger);

        $flight = $this->getFlightInfo($ticket->getFlightId());

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


    private function getTicketDataToBook($ticketId): Ticket
    {
        $ticket = $this->ticketRepository->find((int)$ticketId);
        if (null === $ticket) {
            throw new TicketNotFoundException(sprintf('Ticket id:%s not found', $ticketId));
        }

        if ($ticket->getStatus() !== Ticket::STATUS_FREE) {
            throw new TicketBlockedException(sprintf('Ticket id:%d blocked', $ticket->getId()));
        }

        return $ticket;
    }

    private function getTicketDataToSale($ticketId, $passenger): Ticket
    {
        $ticket = $this->ticketRepository->find((int)$ticketId);
        if (null === $ticket) {
            throw new TicketNotFoundException(sprintf('Ticket id:%s not found', $ticketId));
        }

        if ($ticket->getStatus() === Ticket::STATUS_SOLD) {
            throw new TicketBlockedException(sprintf('Ticket id:%d sold', $ticket->getId()));
        }

        if (
            $ticket->getStatus() === Ticket::STATUS_BOOKED &&
            $ticket->getPassengerId() !== $passenger->getId()
        ) {
            throw new TicketBlockedException(sprintf('Ticket id:%d booked', $ticket->getId()));
        }

        return $ticket;
    }

    private function getFlightInfo(int $id): Flight
    {
        $flight = $this->flightRepository->find($id);
        if ($flight === null) {
            throw new FlightNotFoundException(sprintf('Flight id:%d not found', $id));
        } else if (!$flight->getIsOnSale()) {
            throw new FlightSalesBlocked(sprintf('Flight id:%d sales blocked', $flight->getId()));
        }

        return $flight;
    }

}
