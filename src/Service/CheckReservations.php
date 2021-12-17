<?php

namespace App\Service;

use App\Repository\ReservationsRepository;
use DateTime;

class CheckReservations
{

    public function check(int $announceID, ReservationsRepository $reservationsRepository):array
    {

        $reservations = $reservationsRepository->findReservationsByAnnounce($announceID);

        return $reservations;
    }
}