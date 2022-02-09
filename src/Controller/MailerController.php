<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MailerController extends AbstractController
{
    /**
     * @Route("/mailer", name="mailer")
     */
    public function sendEmail(MailerInterface $mailer, AuthenticationUtils $authenticationUtils): Response
    {   

        $lastUsername = $authenticationUtils->getLastUsername();

        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]); 

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $email=(new Email())
        ->from('carrascoalexis_30@outlook.com')
        ->to('alex_20@gmail.com')
        ->subject('BIENVENIDO!!!')
        ->text('Hola que tal!');
        $mailer->send($email);
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
            'user' => $lastUsername,
            'usuario' =>$min,
        ]);
    }


    /**
     * @Route("/{id}/mailerSuscripcion", name="mailer_suscripcion")
     */
    public function sendEmailSuscripcion(MailerInterface $mailer, AuthenticationUtils $authenticationUtils, Request $request, Publicacion $publicacion, EntityManagerInterface $entityManager): Response
    {   

        $lastUsername = $authenticationUtils->getLastUsername();

        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);
        
        $repository1=$this->getDoctrine()->getRepository(Suscripcion::class);
        $min1 = $repository->findOneBy(['id']);

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $email=(new Email())
        ->from('carrascoalexis_30@outlook.com')
        ->to('alex_20@gmail.com')
        ->subject('BIENVENIDO!!!')
        ->text('Hola que tal!');
        $mailer->send($email);
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
            'user' => $lastUsername,
            'usuario' =>$min,
        ]);
    }
}
