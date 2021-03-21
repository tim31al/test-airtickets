<?php

namespace App\Controller;


use App\Repository\TicketRepository;
use App\Service\TicketService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TicketController extends AbstractController
{
    protected EntityManagerInterface $entityManager;
    protected TicketService $ticketService;

    /**
     * TicketController constructor.
     * @param EntityManagerInterface $entityManager
     * @param TicketService $ticketService
     */
    public function __construct(EntityManagerInterface $entityManager, TicketService $ticketService)
    {
        $this->entityManager = $entityManager;
        $this->ticketService = $ticketService;
    }


    /**
     * @Route("/api/v1/tickets/flight-{flightId}", name="tickets")
     * @param TicketRepository $repository
     * @param int $flightId
     * @return Response
     */
    public function index(TicketRepository $repository, int $flightId): Response
    {
        $tickets = $repository->findByFlight($flightId);
        return $this->json($tickets);
    }


    /**
     * @Route("/api/v1/tickets/buy", methods="POST", name="buy_ticket")
     * @param Request $request
     * @return Response
     */
    public function buy(Request $request): Response
    {
        list ($ticketId, $passenger) = $this->getRequestData($request);

        try {
            $data = $this->ticketService->buyOrBook($ticketId, $passenger);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json($data, 201);
    }

    /**
     * @Route("/api/v1/tickets/booking", methods="POST", name="book_ticket")
     * @param Request $request
     * @return Response
     */
    public function booking(Request $request): Response
    {
        list ($ticketId, $passenger) = $this->getRequestData($request);

        try {
            $data = $this->ticketService->buyOrBook($ticketId, $passenger, true);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json($data, 201);
    }

    /**
     * @Route("/api/v1/tickets/cancel-reservation", methods="POST", name="cancel_reservation_ticket")
     * @param Request $request
     * @return Response
     */
    public function cancelReservation(Request $request): Response
    {
        try {
            $content = json_decode($request->getContent(), true);
            $ticketId = $content['ticketId'];

            $data = $this->ticketService->cancelResevation($ticketId);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        return $this->json($data, 201);
    }



    private function getRequestData(Request $request): array
    {
        $data = json_decode($request->getContent(), true);
        $ticketId = $data['ticketId'];
        $passenger = $data['passenger'];

        return [$ticketId, $passenger];
    }

}
