<?php

namespace App\Controller;

use App\Entity\Announces;
use App\Form\AnnouncesType;
use Doctrine\ORM\Mapping\Id;
use App\Service\UploadService;
use App\Service\CheckReservation;
use App\Repository\UserRepository;
use App\Service\CheckReservations;
use Doctrine\DBAL\Types\StringType;
use App\Repository\AnnouncesRepository;
use App\Repository\ReservationsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/announces')]
class AnnouncesController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/', name: 'announces_index', methods: ['GET'])]
    public function index(AnnouncesRepository $announcesRepository): Response
    {
        
        return $this->render('announces/index.html.twig', [
            'announces' => $announcesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'announces_new', methods: ['GET','POST'])]
    public function new(Request $request, UploadService $uploader): Response
    {
        $announce = new Announces();
        $form = $this->createForm(AnnouncesType::class, $announce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('images')->getData();
            if($imageFile)
            {
                $fileName = $uploader->upload($imageFile);

                $announce->setImages($fileName);
            }

            $user = $this->getUser();
            $announce->setUserID($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($announce);
            $entityManager->flush();

            return $this->redirectToRoute('announces_show', [
                'id' => $announce->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('announces/new.html.twig', [
            'announce' => $announce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', defaults: ['start', 'end'], name: 'announces_show', methods: ['GET','POST'])]
    public function show(Announces $announce, Request $request, UserRepository $userRepository, CheckReservations $checker, ReservationsRepository $reservationsRepository): Response
    {
        $activeUser = $this->security->getUser();
        $city = $request->attributes->get('announce')->getCity();
        $referer = $request->headers->get('referer');

        $reservations = $checker->check($announce->getId(), $reservationsRepository);
        $resa = [];
        foreach( $reservations as $reservation)
        {
            $resa[] = [

                'id' => $reservation->getId(),
                'start' => $reservation->getStart()->format('Y-m-d H:i:s'),
                'end' => $reservation->getEnd()->format('Y-m-d H:i:s'),
                'title' => 'Contrat',
            ];
        }

        $data =json_encode($resa);

        
        $user = $userRepository->findUserByID($announce->getUserID())[0];
        // dd($announce->getUserID());

        if($activeUser == $user)
        {
            return $this->render('announces/show.html.twig', [
                'announce' => $announce,
                'previousPage' => $referer,
                'user' => $user,
                'activeUser' => $activeUser,
                'previousCity' => $city,
                'data' => $data
            ]);
        }
        else
        {
            
            $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('reservation_book', array(
                'id' => $announce->getId(),
                'user' => $user,
                )))
            ->add('start', DateType::class, array(
                'label' => "Du  ",
                'required' => true,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'autocomplete' => "off",
                    'placeholder' => "Arrivée",
                    'class' => 'js-datepicker inputStart',
                    'id' => 'start',
                    'data-provide' => 'datetimepicker',
                ],
                ))
            ->add('end', DateType::class, array(
                'label' => "Au  ",
                'required' => true,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'autocomplete' => "off",
                    'placeholder' => "Départ",
                    'class' => 'js-datepicker inputEnd',
                    'id' => 'end',
                    'data-provide' => 'datetimepicker',
                ],
                ))
            ->add('book', SubmitType::class, [
                'label' => "Réserver",
                'attr' => [
                    'class' => 'btn mt-3 primary'
                ],
            ])
            ->getForm();

            dump($form->get('end')->getData() - $form->get('start')->getData());

            return $this->render('announces/show.html.twig', [
                'announce' => $announce,
                'previousPage' => $referer,
                'user' => $user,
                'activeUser' => $activeUser,
                'previousCity' => $city,
                'form' => $form->createView(),
                'data' => $data
            ]);
        }
    }

    #[Route('/{id}/edit', name: 'announces_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Announces $announce, UploadService $uploader): Response
    {
        $form = $this->createForm(AnnouncesType::class, $announce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('images')->getData();
            if($imageFile == null)
            {
                $announce->setImages($announce->getImages());
            }
            else
            {
                $fileName = $uploader->upload($imageFile);

                $announce->setImages($fileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('announces_show', [
                'id' => $announce->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('announces/edit.html.twig', [
            'announce' => $announce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'announces_delete', methods: ['GET','POST'])]
    public function delete(Request $request, Announces $announce): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($announce);
            $entityManager->flush();

        return $this->redirectToRoute('home_index', [], Response::HTTP_SEE_OTHER);
    }

}
