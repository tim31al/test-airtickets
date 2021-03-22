<?php

namespace App\Repository;

use App\Entity\Flight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Flight|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flight|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flight[]    findAll()
 * @method Flight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Flight::class);
    }

    public function findOpened()
    {
        return $this->createQueryBuilder('f')
            ->select('f.id, f.company, f.departure, f.arrival, f.seatsNumber, f.departureTime')
            ->andWhere('f.isOnSale = true')
            ->getQuery()
            ->getResult();
    }
}
