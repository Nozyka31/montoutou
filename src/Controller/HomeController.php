<?php

namespace App\Controller;

use DateTime;
use App\Service\Geocoding;
use Symfony\Component\Mime\Email;
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

            for($i = 0; $i < sizeof($announces); $i++)
            {
                if($reservationsRepository->findReservationsByAnnounce($announces[$i]))
                {
                    $resa = $reservationsRepository->findReservationsByAnnounce($announces[$i]);
                    for($j = 0; $j < count($resa); $j++)
                    {
                        if($startDate >= $resa[$j]->getEnd() || $endDate <= $resa[$j]->getStart())
                        {
                        }
                        else
                        {
                            if($announces[$i] == $resa[$j]->getAnnounceId())
                            {
                                unset($announces[$i]);
                                $i--;
                            }
                        }
                    }
                }

                $addressCoordinates[$i] = $this->geoCode($announces[$i]->getAddress() . ", " . $announces[$i]->getPostcode() . ", " . $announces[$i]->getCity(), $announces[$i]->getId());

            }
        }

        return $this->render('announces/displaySearch.html.twig', [
            'announces' => $announces,
            'addressCoordinates' => $addressCoordinates,
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
    public function account(ReservationsRepository $reservationsRepository): Response
    {
        $user = $this->security->getUser();
        $resClient = $reservationsRepository->findReservationsByClient($user);
        $resGardien = $reservationsRepository->findReservationsByGardien($user);

        return $this->render('home/account.html.twig', [
            'user' => $user,
            'clients' => $resClient,
            'gardiens' => $resGardien,
        ]);
    }
}
