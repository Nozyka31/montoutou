<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function index(): Response
    {
        $form = $this->createFormBuilder(null)
            ->add('city', TypeTextType::class, [
                'label' => "Ville",
                'required' => true,
                'attr' => [
                    'placeholder' => "Entrez le nom de le ville ou vous souhaitez faire garder votre annimal",
                ],
            ])
            ->add('')
            ->add('search', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
            ])
            ->getForm();

        return $this->render('search/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
