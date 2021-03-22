<?php


namespace App\MessageHandler;


use App\Message\FlightMessage;
use App\Repository\FlightRepository;
use App\Repository\PassengerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class FlightEventMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;
    private PassengerRepository $passengerRepository;
    private FlightRepository $flightRepository;
    private MailerInterface $mailer;


    /**
     * FlightEventMessageHandler constructor.
     * @param PassengerRepository $passengerRepository
     * @param FlightRepository $flightRepository
     * @param EntityManagerInterface $em
     * @param MailerInterface $mailer
     */
    public function __construct(
        PassengerRepository $passengerRepository,
        FlightRepository $flightRepository,
        EntityManagerInterface $em,
        MailerInterface $mailer
    )
    {
        $this->passengerRepository = $passengerRepository;
        $this->flightRepository = $flightRepository;
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function __invoke(FlightMessage $message)
    {
        $id = $message->getId();
        $context = $message->getContext();

        try {
            $emails = array_values($this->passengerRepository->findEmailsByFlight($id));
            $flight = $this->flightRepository->find((int)$id);

            $message = sprintf(
                'Your flight %s - %s at %s %s',
                $flight->getDeparture(),
                $flight->getArrival(),
                $flight->getDepartureTime()->format('d.m.Y H:m'),
                $context['message']
            );

            $email = (new Email())
                ->from('ticket-service@mail.com')
                ->to(...$emails)
                ->subject('Flight status')
                ->html($message);

            $this->mailer->send($email);

        } catch (TransportExceptionInterface $e) {
            echo $e->getMessage();
        }
    }
}
