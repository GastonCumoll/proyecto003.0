<?php

namespace App\Controller;

use DateTime;
use App\Entity\Edicion;
use App\Form\EdicionType;
use App\Entity\Publicacion;
use App\Entity\suscripcion;
use App\Form\PublicacionType;
use App\Repository\EdicionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PublicacionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Repository\SuscripcionRepository;
/**
 * @Route("/publicacion")
 */
class PublicacionController extends AbstractController
{
    /**
     * @Route("/", name="publicacion_index", methods={"GET"})
     */
    public function index(PublicacionRepository $publicacionRepository, AuthenticationUtils $authenticationUtils): Response
    {   
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        return $this->render('publicacion/index.html.twig', [
            'publicacions' => $publicacionRepository->findAll(),
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    /**
     * @Route("/new", name="publicacion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        $session = new Session(new NativeSessionStorage(), new AttributeBag());
        

        
        $publicacion = new Publicacion();
        $form = $this->createForm(PublicacionType::class, $publicacion,);
        $form->handleRequest($request);

        $publicaciones=$this->getDoctrine()->getRepository(Publicacion::class)->findAll();
        

        if ($form->isSubmitted() && $form->isValid()) {

            //se verifica que no se persistan tipos de publicaciones repetidos 
            foreach( $publicaciones as $unaPublicacion)
            {
                if(($unaPublicacion->getTitulo()==$publicacion->getTitulo())&&($unaPublicacion->getUsuarioCreador()->getId()==$publicacion->getUsuarioCreador()->getId())&&($unaPublicacion->getTipoPublicacion()->getNombre()==$publicacion->getTipoPublicacion()->getNombre()))
                {
                    return $this->redirectToRoute('publicacion_index', [], Response::HTTP_SEE_OTHER);
                }
            }
            $today=new DateTime();
            $publicacion->setFechaYHora($today);
            $edicion= new Edicion();
            $edicion->setFechaDeEdicion($publicacion->getFechaYHora());
            $edicion->setFechaYHoraCreacion($publicacion->getFechaYHora());
            $edicion->setUsuarioCreador($publicacion->getUsuarioCreador());
            $edicion->setCantidadImpresiones($form->get('cantidadImpresiones')->getData());
            $edicion->setPublicacion($publicacion);

            $entityManager->persist($publicacion);
            $entityManager->persist($edicion);
            $entityManager->flush();
            //$id=$publicacion->getId();
            

            

            return $this->redirectToRoute('publicacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publicacion/new.html.twig', [
            'publicacion' => $publicacion,
            'form' => $form,
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    
    /**
     * @Route("/mostrarPublicaciones", name="publicacion_mostrar", methods={"GET"})
     */
    public function mostrar(PublicacionRepository $publicacionRepository, SuscripcionRepository $suscripcionRepository, EntityManagerInterface $em, AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        $em=$this->getDoctrine()->getManager();

        //En este caso, $publicaciones no tiene las publicaciones sino las ediciones que pertenecen a una determinada publicacion del tipo que el usuario esta suscripto.
        $publicaciones=$em->getRepository(Suscripcion::class)->buscarTipoPublicacion($min->getId());

        return $this->render('publicacion/mostrarPublicaciones.html.twig', [
            'publicaciones' => $publicaciones,
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    /**
     * @Route("/publicacionUsuario", name="publicacion_usuario", methods={"GET"})
     */
    public function publicacionesUsuario(PublicacionRepository $publicacionRepository, AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        return $this->render('publicacion/publicacionesUsuario.html.twig', [
            'publicaciones' => $publicacionRepository->findBy(['usuarioCreador'=>$min]),
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    /**
     * @Route("/{id}", name="publicacion_show", methods={"GET"})
     */
    public function show(Publicacion $publicacion, AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        return $this->render('publicacion/show.html.twig', [
            'publicacion' => $publicacion,
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="publicacion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Publicacion $publicacion, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        $form = $this->createForm(PublicacionType::class, $publicacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('publicacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publicacion/edit.html.twig', [
            'publicacion' => $publicacion,
            'form' => $form,
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    /**
     * @Route("/{id}", name="publicacion_delete", methods={"POST"})
     */
    public function delete(Request $request, Publicacion $publicacion, EntityManagerInterface $entityManager): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        if ($this->isCsrfTokenValid('delete'.$publicacion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publicacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('publicacion_index', [], Response::HTTP_SEE_OTHER);
    }

}
