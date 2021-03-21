<?php

namespace App\DataFixtures;

use App\Entity\Passenger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PassengerFixtures extends Fixture
{
    const FIRST_NAMES = ['Ivan', 'Petr', 'Maria', 'Becker'];
    const LAST_NAMES = ['Ivanov', 'Petrov', 'Mashina', 'Smitt'];
    const EMAILS = ['ivan@mail.com', 'petr@mail.com', 'maria@mail.com', 'backer@mail.com'];

    public function load(ObjectManager $manager)
    {
        $count = count(self::FIRST_NAMES);

        for ($i = 0; $i < $count; $i++) {
            $passenger = new Passenger();
            $passenger->setPassNumber($this->randomPassNumber());
            $passenger->setEmail(self::EMAILS[$i]);
            $passenger->setPassword(self::FIRST_NAMES[$i]);
            $passenger->setFirstname(self::FIRST_NAMES[$i]);
            $passenger->setLastname(self::LAST_NAMES[$i]);

            $manager->persist($passenger);
        }

        $manager->flush();
    }

    private function randomPassNumber()
    {
        return bin2hex(random_bytes(10));
    }
}
