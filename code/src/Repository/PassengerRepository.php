<?php

namespace App\Repository;

use App\Entity\Passenger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Passenger|null find($id, $lockMode = null, $lockVersion = null)
 * @method Passenger|null findOneBy(array $criteria, array $orderBy = null)
 * @method Passenger[]    findAll()
 * @method Passenger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PassengerRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Passenger::class);
        $this->em = $em;
    }

    public function findOrCreate(array $userData): Passenger
    {
        list($email, $firstname, $lastname, $passNumber) = array_values($userData);
        $email = htmlspecialchars($email);

        $passenger = $this->findOneBy(['email' => $email]);

        if ($passenger === null) {

            $firstname = htmlspecialchars($firstname);
            $lastname = htmlspecialchars($lastname);
            $passNumber = htmlspecialchars($passNumber);

            $passenger = new Passenger();
            $passenger->setEmail($email);
            $passenger->setFirstname($firstname);
            $passenger->setLastname($lastname);
            $passenger->setPassNumber($passNumber);
            $passenger->setPassword(bin2hex(random_bytes(10)));

            $this->em->persist($passenger);
            $this->em->flush();
        }

        return $passenger;
    }


    // /**
    //  * @return Passenger[] Returns an array of Passenger objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Passenger
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
