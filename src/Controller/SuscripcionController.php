<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use DateTimeInterface;
use App\Entity\TipoPublicacion;
use App\Entity\Suscripcion;
use App\Form\SuscripcionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SuscripcionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\EventListener\SuscripcionSubscriber;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/suscripcion")
 */
class SuscripcionController extends AbstractController
{
    /**
     * @Route("/", name="suscripcion_index", methods={"GET"})
     */
    public function index(SuscripcionRepository $suscripcionRepository, AuthenticationUtils $authenticationUtils): Response
    {   
        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        return $this->render('suscripcion/index.html.twig', [
            'suscripcions' => $suscripcionRepository->findAll(),
            'usuario' =>$min,
            'user' =>$lastUsername,
            'activo'=> 1,
        ]);
    }
    /**
     * @Route("/suscripcionUsuario", name="suscripcion_usuario", methods={"GET"})
     */
    public function suscripcionesUsuario(SuscripcionRepository $suscripcionRepository, AuthenticationUtils $authenticationUtils): Response
    {   
        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        return $this->render('suscripcion/suscripcionesUsuario.html.twig', [
            'suscripciones' => $suscripcionRepository->findBy(['usuario'=>$min]),
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    /**
     * @Route("/{id}, newsuscripcion", name="nueva_suscripcion", methods={"GET", "POST"})
     */
    public function newSuscripcion(SuscripcionRepository $suscripcionRepository, Request $request, EntityManagerInterface $entityManager,$id, AuthenticationUtils $authenticationUtils): Response
    {   

        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);
        $tipoPublicacion=$this->getDoctrine()->getRepository(TipoPublicacion::class)->findOneBy(['id'=>$id]);

        $suscripcion=new Suscripcion();
        $session=$request->getSession();
        $idUser=$session->get('id');
        $usuario=$this->getDoctrine()->getRepository(User::class)->findOneBy(['id'=>$idUser]);
        

        $today=new DateTime();
        $suscripcion->setFechaSuscripcion($today);
        $suscripcion->setUsuario($usuario);
        $suscripcion->setTipoPublicacion($tipoPublicacion);


        //este algoritmo comprueba que no se persistan suscripciones repetidas, en ese caso no persiste y vuelve al suscripción index.
        $suscripciones=$this->getDoctrine()->getRepository(Suscripcion::class)->findAll();
        foreach( $suscripciones as $unaSuscripcion){
            if(($unaSuscripcion->getusuario()==$suscripcion->getusuario())&&($unaSuscripcion->getTipoPublicacion()==$suscripcion->getTipoPublicacion())){
                return $this->renderForm('suscripcion/index.html.twig', [
                    'suscripcions' => $suscripcionRepository->findAll(),
                    'usuario' =>$min,
                    'user' =>$lastUsername,
                ]);
            }
        }



        $entityManager->persist($suscripcion);
        $entityManager->flush();



        return $this->renderForm('suscripcion/index.html.twig', [
            'suscripcions' => $suscripcionRepository->findAll(),
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
        
    }

    /**
     * @Route("/new", name="suscripcion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        $suscripcion = new Suscripcion();
        $today=new DateTime();
        
        $form = $this->createForm(SuscripcionType::class, $suscripcion);
        
        $form->handleRequest($request);

        $suscripcion->setfechaSuscripcion($today);

        $suscripciones=$this->getDoctrine()->getRepository(Suscripcion::class)->findAll();
        

        if ($form->isSubmitted() && $form->isValid()) {
            foreach( $suscripciones as $unaSuscripcion){
                if(($unaSuscripcion->getusuario()==$suscripcion->getusuario())&&($unaSuscripcion->getTipoPublicacion()==$suscripcion->getTipoPublicacion())){
                    return $this->renderForm('suscripcion/index.html.twig', [
                        'suscripcions' => $suscripciones,
                        'usuario' =>$min,
                        'user' =>$lastUsername,
                    ]);
                }
            }

            $entityManager->persist($suscripcion);
            $entityManager->flush();
        
            return $this->redirectToRoute('suscripcion_index', [], Response::HTTP_SEE_OTHER);
            
        }

        return $this->renderForm('suscripcion/new.html.twig', [
            'suscripcion' => $suscripcion,
            'form' => $form,
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    

    /**
     * @Route("/{id}", name="suscripcion_show", methods={"GET"})
     */
    public function show(Suscripcion $suscripcion, AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        return $this->render('suscripcion/show.html.twig', [
            'suscripcion' => $suscripcion,
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="suscripcion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Suscripcion $suscripcion, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        $form = $this->createForm(SuscripcionType::class, $suscripcion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('suscripcion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('suscripcion/edit.html.twig', [
            'suscripcion' => $suscripcion,
            'form' => $form,
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    /**
     * @Route("/{id}", name="suscripcion_delete", methods={"POST"})
     */
    public function delete(Request $request, Suscripcion $suscripcion, EntityManagerInterface $entityManager): Response
    {
        
        
        if ($this->isCsrfTokenValid('delete'.$suscripcion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($suscripcion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('suscripcion_index', [], Response::HTTP_SEE_OTHER);
    }
}
