<?php

namespace App\Controller;


use App\Repository\FlightRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightController extends AbstractController
{
    /**
     * @Route("/api/v1/flights", name="flight")
     * @param FlightRepository $flightRepository
     * @return Response
     */
    public function index(FlightRepository $flightRepository): Response
    {
        $flights = $flightRepository->findBy(['isOnSale' => true]);

        return $this->json(
            array_map(fn($item) => $item->toPrint(), $flights)
        );
    }
}
