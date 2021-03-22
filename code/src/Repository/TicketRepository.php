<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function findByFlight($id)
    {
        return $this->createQueryBuilder('t')
            ->select('t.id, t.flightId, t.seat, t.status, t.passengerId')
            ->join('App\Entity\Flight', 'f', 'WITH', 'f.id = :id')
            ->andWhere('f.isOnSale = true')
            ->andWhere('t.flightId = :id')
            ->andWhere('t.status = :status')
            ->setParameter('id', $id)
            ->setParameter('status', Ticket::STATUS_FREE)
            ->orderBy('t.seat')
            ->getQuery()
            ->getResult();
    }


}
