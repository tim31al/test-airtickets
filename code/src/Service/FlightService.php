<?php


namespace App\Service;


use App\Exceptions\FlightNotFoundException;
use App\Exceptions\FlightSalesBlocked;
use App\Repository\FlightRepository;
use Doctrine\ORM\EntityManagerInterface;

class FlightService
{
    const EVENT_TICKETS_COMPLETED = 'flight_ticket_sales_completed';
    const EVENT_FLIGHT_CANCELED = 'flight_canceled';

    private FlightRepository $flightRepository;
    private EntityManagerInterface $em;

    /**
     * FlightService constructor.
     * @param FlightRepository $flightRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(FlightRepository $flightRepository, EntityManagerInterface $em)
    {
        $this->flightRepository = $flightRepository;
        $this->em = $em;
    }

    public function ticketsCompleted($flightId)
    {
        $flight = $this->flightRepository->find((int) $flightId);

        if (null ===$flight) {
            throw new FlightNotFoundException(sprintf('Flight id:%s not found', $flightId));
        }

        if (!$flight->getIsOnSale()) {
            throw new FlightSalesBlocked(sprintf('Flight id:%d sales is already blocked', $flight->getId()));
        }

        $flight->setIsOnSale(false);
        $this->em->persist($flight);
        $this->em->flush();
    }

    public function flightCanceled($flightId)
    {
        $flight = $this->flightRepository->find((int) $flightId);

        if (null ===$flight) {
            throw new FlightNotFoundException(sprintf('Flight id:%s not found', $flightId));
        }

        $flight->setIsOnSale(false);
        $this->em->persist($flight);
        $this->em->flush();
    }

}
