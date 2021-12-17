<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Entity\User;
use App\Form\MessagesType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    #[Route('/messages', name: 'messages')]
    public function index(): Response
    {
        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }

    #[Route('/send', name: 'messages_send')]
    public function send(Request $request, UserRepository $userRepository): Response
    {
        if($request->query->get('id') != null)
        {
            $user = $userRepository->findUserByID($request->query->get('id'));
        }
        $message = new Messages;
        $form = $this->createForm(MessagesType::class, $message);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $message->setSender($this->getUser());
            $message->setRecipient($user[0]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("message", "Message envoyé avec succès.");

            return $this->redirectToRoute("messages");
        }

        return $this->render("messages/send.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    #[Route('/received', name: 'messages_received')]
    public function received(): Response
    {
        return $this->render('messages/received.html.twig');
    }

    #[Route('/sent', name: 'messages_sent')]
    public function sent(): Response
    {
        return $this->render('messages/sent.html.twig');
    }

    #[Route('/read/{id}', name: 'messages_read')]
    public function read(Messages $message): Response
    {
        $message->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        return $this->render('messages/read.html.twig', compact("message"));
    }

    #[Route('/delete/{id}', name: 'messages_delete')]
    public function delete(Messages $message): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute('messages_received');
    }
}
