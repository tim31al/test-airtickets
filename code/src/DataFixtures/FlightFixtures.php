<?php

namespace App\DataFixtures;

use App\Entity\Flight;
use App\Entity\Ticket;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FlightFixtures extends Fixture
{
    const MIN_SEATS_COUNT = 100;
    const MAX_SEATS_COUNT = 150;

    const DEPARTURES = ['Moscow', 'Sochi', 'Amsterdam' ];
    const ARRIVALS = ['Berlin', 'Moscow', 'New York'];
    const COMPANIES = ['AER', 'BDX', 'GBT'];

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 3; $i++) {

            $flight = new Flight();

            $flight->setCompany(self::COMPANIES[$i]);
            $flight->setDeparture(self::DEPARTURES[$i]);
            $flight->setArrival(self::ARRIVALS[$i]);

            $date = $this->generateDate();

            $flight->setDepartureTime($date);
            $flight->setIsOnSale(true);

            $seatsNumber = rand(self::MIN_SEATS_COUNT, self::MAX_SEATS_COUNT);

            $flight->setSeatsNumber($seatsNumber);
            $manager->persist($flight);

            for ($j = 1; $j <= $seatsNumber; $j++) {
                $ticket = new Ticket();
                $ticket->setSeat($j);
                $ticket->setFlightId($flight->getId());
                $ticket->setStatus(Ticket::STATUS_FREE);

                $manager->persist($ticket);
            }
        }

        $manager->flush();
    }

    private function generateDate(): DateTime
    {
        $date = new DateTime('NOW');
        $day = rand(1, 3);
        $hour = rand(1, 23);
        $minute = rand(0, 59);

        $date->add(new \DateInterval("P${day}DT${hour}H${minute}M00S"));

        return $date;
    }
}
