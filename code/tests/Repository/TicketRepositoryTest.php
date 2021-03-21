<?php


use App\Entity\Ticket;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TicketRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFind()
    {
        $tickets = $this->entityManager
            ->getRepository(Ticket::class)
            ->findByFlight(1);


        $this->assertCount(116, $tickets);
    }

}
