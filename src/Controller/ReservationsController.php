<?php

namespace App\Controller;

use DateTime;
use DateTimeInterface;
use App\Entity\Announces;
use App\Entity\Reservations;
use App\Repository\UserRepository;
use App\Repository\AnnouncesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationsController extends AbstractController
{
    #[Route('/reservations', name: 'reservations')]
    public function index(): Response
    {
        return $this->render('reservations/index.html.twig', [
            'controller_name' => 'ReservationsController',
        ]);
    }

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/book/{id}', name: 'reservation_book', methods: ['GET','POST'], options:[])]
    public function book(Request $request, UserRepository $userRepository, AnnouncesRepository $announcesRepository): Response
    {
        
        $id = $request->attributes->get("id");
        $announce = $announcesRepository->findOneByID($id);
        $data = $request->request->all();

        $format = 'Y-m-d';
        $start = $request->request->get(key:'form')['start'];
        $startDate = DateTime::createFromFormat($format, $start);
        $end = $request->request->get(key:'form')['end'];
        $endDate = DateTime::createFromFormat($format, $end);
        $gardien = $userRepository->findUserByID($announce->getUserID())[0];
        $client = $userRepository->findUserByID($this->security->getUser())[0];


        $reservation = new Reservations;
        $reservation->setClientId($client);
        $reservation->setGardienId($gardien);
        $reservation->setAnnounceId($announce);
        $reservation->setStart($startDate);
        $reservation->setEnd($endDate);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservation);
        
        $entityManager->flush();

        //dd($startDate);
        //Sur la page annonce, afficher les dates occupés sur le calendrier
        //Récupérer les jours sélectionnés par le Client
        //Récupérer l'id GARDIEN et CLIENT
        //Envoyer à la base des reservations, le GARDIEN, le CLIENT, l'ANNONCE, et les dates.
        //Sur la page book -> confirmation des jours, récap de l'annonce & Laisser un message au gardien

        return $this->render('reservations/book.html.twig', [
            'data' => $data,
            'announce' => $announce,
            'gardien' => $gardien,
            'client' => $client,
        ]);
    }
}
