<?php

namespace App\Controller;

use DateTime;
use App\Entity\Edicion;
use App\Form\EdicionType;
use App\Repository\EdicionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Entity\TipoPublicacion;

/**
 * @Route("/edicion")
 */
class EdicionController extends AbstractController
{
    /**
     * @Route("/", name="edicion_index", methods={"GET"})
     */
    public function index(EdicionRepository $edicionRepository, AuthenticationUtils $authenticationUtils): Response
    {   


        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        return $this->render('edicion/index.html.twig', [
            'edicions' => $edicionRepository->findAll(),
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }
    
    /**
     * @Route("/new", name="edicion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

            $session=$request->getSession();
            $token=$session->get('objetoP');
        
            $edicion=new Edicion();
            $form=$this->createForm(EdicionType::class, $edicion,);
            $form->handleRequest($request);

            $ediciones=$this->getDoctrine()->getRepository(edicion::class)->findAll();
            $today= new DateTime();
            if ($form->isSubmitted() && $form->isValid()) {
                foreach( $ediciones as $unaEdicion)
                {
                    if(($unaEdicion->getFechaDeEdicion()==$edicion->getFechaDeEdicion()) || ($edicion->getCantidadImpresiones()<0) || ($edicion->getFechaDeEdicion()>$today))
                    {
                        return $this->redirectToRoute('edicion_index', [], Response::HTTP_SEE_OTHER);
                    }
                }
            
                $edicion->setFechaYHoraCreacion($today);
                $entityManager->persist($edicion);
                $entityManager->flush();
            
                return $this->redirectToRoute('edicion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('edicion/new.html.twig', [
            'edicion' => $edicion,
            'form' => $form,
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    
    /**
     * @Route("/edicionUsuario", name="edicion_usuario", methods={"GET"})
     */
    public function edicionesUsuario(EdicionRepository $edicionRepository, AuthenticationUtils $authenticationUtils): Response
    {   


        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        return $this->render('edicion/edicionesUsuario.html.twig', [
            'ediciones' => $edicionRepository->findBy(['usuarioCreador'=>$min]),
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    /**
     * @Route("/{id}", name="edicion_show", methods={"GET"})
     */
    public function show(Edicion $edicion, AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        return $this->render('edicion/show.html.twig', [
            'edicion' => $edicion,
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="edicion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Edicion $edicion, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
    {   
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        $form = $this->createForm(EdicionType::class, $edicion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('edicion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('edicion/edit.html.twig', [
            'edicion' => $edicion,
            'form' => $form,
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    /**
     * @Route("/{id}", name="edicion_delete", methods={"POST"})
     */
    public function delete(Request $request, Edicion $edicion, EntityManagerInterface $entityManager): Response
    {   
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        if ($this->isCsrfTokenValid('delete'.$edicion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($edicion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('edicion_index', [], Response::HTTP_SEE_OTHER);
    }
}
