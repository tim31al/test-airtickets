<?php

namespace App\Controller;

use App\Message\FlightMessage;
use App\Repository\FlightRepository;
use App\Service\FlightService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    private FlightService $flightService;
    private MessageBusInterface $bus;


    /**
     * EventController constructor.
     * @param FlightService $flightService
     * @param MessageBusInterface $bus
     */
    public function __construct(FlightService $flightService, MessageBusInterface $bus)
    {
        $this->flightService = $flightService;
        $this->bus = $bus;
    }


    /**
     * @Route("/api/v1/callback/events", methods="POST", name="event")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        try{
            list($data) = array_values(json_decode($request->getContent(), true));
            list($ticketId, $triggerAt, $event, $secret) = array_values($data);

            switch ($event) {
                case FlightService::EVENT_TICKETS_COMPLETED:
                    $this->flightService->ticketsCompleted($ticketId);
                    break;

                case FlightService::EVENT_FLIGHT_CANCELED:
                    $this->flightService->flightCanceled($ticketId);
                    $this->bus->dispatch(new FlightMessage($ticketId, ['message' => 'closed']));
                    break;
            }

            return new Response();
        } catch (Exception $e) {
            return $this->json([
                'error' => $e->getMessage(),
                'data' => $data
            ], 400);
        }
    }

}
