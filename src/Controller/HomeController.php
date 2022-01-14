<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\Geocoding;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use App\Repository\AnnouncesRepository;
use App\Repository\ReservationsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class HomeController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/', name: 'home_index')]
    public function index(): Response
    {
        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl(route:'home_search'))
            ->add('city', TextType::class, array(
                'label' => "Ville  ",
                'required' => true,
                'attr' => [
                    'placeholder' => "Cherchez une ville",
                    'class' => 'ms-1',
                ],
            ))
            ->add('start', DateTimeType::class, array(
                'label' => "Du  ",
                'required' => true,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'autocomplete' => "off",
                    'placeholder' => "Cliquez pour sélectionner une date",
                    'class' => 'js-datepicker',
                    'data-provide' => 'datetimepicker',
                ],
                ))
            ->add('end', DateTimeType::class, array(
                'label' => "Au  ",
                'required' => true,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'autocomplete' => "off",
                    'placeholder' => "Cliquez pour sélectionner une date",
                    'class' => 'js-datepicker',
                    'data-provide' => 'datetimepicker',
                ],
                ))
            ->add('search', SubmitType::class, [
                'label' => "Rechercher",
                'attr' => [
                    'class' => 'btn primary'
                ],
            ])
            ->getForm();

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function geoCode(string $address, int $id)
    {
        $geo = new Geocoding();
        $coordinates = $geo->getCoordinates($address, $id);
        
        
        return $coordinates;
    }

    #[Route('/handleSearch', name: 'home_search')]
    public function handleSearch(Request $request, AnnouncesRepository $announcesRepository, ReservationsRepository $reservationsRepository)
    {
        //dd($request->request->get(key:'form'));
        $startDate = $request->request->get(key:'form')['start'];   	
        $startDate = new \DateTime($startDate);
        $endDate = $request->request->get(key:'form')['end'];
        $endDate = new \DateTime($endDate);
        if($request->query->get('previousCity') == null)
        {
            $query = $request->request->get(key:'form')['city'];
        }
        else
        {
            $query = $request->query->get('previousCity');
        }
        if($query)
        {
            $announces = $announcesRepository->findAnnouncesByCity($query);


            $addressCoordinates = array();
            $indexRemoved = array();

            for($i = 0; $i < sizeof($announces); $i++)
            {
                // dd($reservationsRepository->findReservationsByAnnounce($announces[3])[0]);
                if($reservationsRepository->findReservationsByAnnounce($announces[$i]))
                {
                    $resa = $reservationsRepository->findReservationsByAnnounce($announces[$i]);
                    foreach($resa as $res)
                    {
                        if($startDate >= $res->getEnd() || $endDate <= $res->getStart())
                        {
                            if($indexRemoved[$i] =! true)
                            {
                                $indexRemoved[$i] = false;
                            }
                        }
                        else
                        {
                            $indexRemoved[$i] = true;
                        }
                    }
                }
                else
                {
                    $indexRemoved[$i] = false;
                }

                if($indexRemoved[$i] == false)
                {
                    $addressCoordinates[$i] = $this->geoCode($announces[$i]->getAddress() . ", " . $announces[$i]->getPostcode() . ", " . $announces[$i]->getCity(), $announces[$i]->getId());
                }
            }

            for($i = 0; $i < sizeof($indexRemoved); $i++)
            {
                if($indexRemoved[$i] == true)
                {
                    unset($announces[$i]);
                }
            }
        }

        return $this->render('announces/displaySearch.html.twig', [
            'announces' => $announces,
            'addressCoordinates' => $addressCoordinates,
            'start' => $startDate,
            'end' => $endDate,
        ]);
    }

    #[Route('/contact', name: 'home_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        if($request->request->all()) {
            $email = new Email();
            $email->to(new Address("noreply@montoutou.net", "Arthur"))
                ->from($request->request->get("email"))
                ->subject($request->request->get("sujet"))
                ->text($request->request->get("message"));
                $mailer->send($email);
                $this->addFlash("success", "Votre message a bien été envoyé !");
                return $this->redirectToRoute('home_index');
        }
        return $this->render('home/contact.html.twig');
    }

    #[Route('/account', name: 'home_account')]
    public function account(ReservationsRepository $reservationsRepository, UserRepository $userRepository, AnnouncesRepository $announcesRepository): Response
    {
        $user = $this->security->getUser();
        $resClient = $reservationsRepository->findReservationsByClient($user);
        $resGardien = $reservationsRepository->findReservationsByGardien($user);
        $announces = $announcesRepository->findAnnouncesByUser($user->getId());

        return $this->render('home/account.html.twig', [
            'user' => $user,
            'resClients' => $resClient,
            'resGardiens' => $resGardien,
            'announces' => $announces,
        ]);
    }

    #[Route('/{id}/edit', name: 'account_edit', methods: ['GET','POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('home_account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home/account_edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
