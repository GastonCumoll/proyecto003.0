<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Suscripcion;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PublicacionRepository;
use App\Repository\SuscripcionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();


        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
    
    
    /**
     * @Route("/logedIn", name="loged_in")
     */
    public function logedIn(AuthenticationUtils $authenticationUtils,Request $request,PublicacionRepository $publicacionRepository, SuscripcionRepository $suscripcionRepository, EntityManagerInterface $em):Response{
        $lastUsername = $authenticationUtils->getLastUsername();

        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]); 
        $session=$request->getSession();
        $session->set('name',$lastUsername);
        $session->set('id',$min->getId());
        
        

        
        //dd($min);
        // $nombre = $this->getDoctrine()->getRepository(Usuario::class);
        // $usuario=$nombre-findBy(array('email'=>$lastUsername));
        $paginaActiva=1;


        $em=$this->getDoctrine()->getManager();

        //En este caso, $publicaciones no tiene las publicaciones sino las ediciones que pertenecen a una determinada publicacion del tipo que el usuario esta suscripto.
        $publicaciones=$em->getRepository(Suscripcion::class)->buscarTipoPublicacion($min->getId());


        return $this->render('login/logedIn.html.twig', [
            'user' => $lastUsername,
            'usuario' =>$min,
            'paginaActiva' => $paginaActiva,
            'publicaciones' => $publicaciones,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): response
    {
        return $this->render('login/index.html.twig', [
        ]);
    }
}

?>