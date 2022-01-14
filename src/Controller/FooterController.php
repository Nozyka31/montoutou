<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FooterController extends AbstractController
{
    #[Route('/fonctionnement', name: 'footer_fonctionnement')]
    public function fonctionnement(): Response
    {
        return $this->render('footer/fonctionnement.html.twig', [
        ]);
    }

    #[Route('/faq', name: 'footer_faq')]
    public function faq(): Response
    {
        return $this->render('footer/faq.html.twig', [
        ]);
    }

    #[Route('/demarche', name: 'footer_demarche')]
    public function demarche(): Response
    {
        return $this->render('footer/demarche.html.twig', [
        ]);
    }
}
