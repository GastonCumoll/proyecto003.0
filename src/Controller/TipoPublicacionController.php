<?php

namespace App\Controller;

use App\Entity\TipoPublicacion;
use App\Form\TipoPublicacionType;
use App\Repository\TipoPublicacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Repository\SuscripcionRepository;
use App\Entity\Suscripcion;

/**
 * @Route("/tipo/publicacion")
 */
class TipoPublicacionController extends AbstractController
{
    /**
     * @Route("/", name="tipo_publicacion_index", methods={"GET"})
     */
    public function index(TipoPublicacionRepository $tipoPublicacionRepository, AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);
        $suscripciones=$this->getDoctrine()->getRepository(Suscripcion::class)->findBy(['usuario'=>$min]);

        return $this->render('tipo_publicacion/index.html.twig', [
            'tipo_publicacions' => $tipoPublicacionRepository->findAll(),
            'usuario' =>$min,
            'user' =>$lastUsername,
            'suscripciones'=>$suscripciones,
        ]);
    }

    /**
     * @Route("/new", name="tipo_publicacion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        $tipoPublicacion = new TipoPublicacion();
        $form = $this->createForm(TipoPublicacionType::class, $tipoPublicacion);
        $form->handleRequest($request);
        $TiposPublicaciones=$this->getDoctrine()->getRepository(TipoPublicacion::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            
            //se verifica que no se persistan tipos de publicaciones repetidos 
            foreach( $TiposPublicaciones as $unTipoPublicacion)
            {
                if($unTipoPublicacion->getNombre()==$tipoPublicacion->getNombre())
                {
                    return $this->redirectToRoute('tipo_publicacion_index', [], Response::HTTP_SEE_OTHER);
                }
            }

            $entityManager->persist($tipoPublicacion);
            $entityManager->flush();

            return $this->redirectToRoute('tipo_publicacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_publicacion/new.html.twig', [
            'tipo_publicacion' => $tipoPublicacion,
            'form' => $form,
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_publicacion_show", methods={"GET"})
     */
    public function show(TipoPublicacion $tipoPublicacion, AuthenticationUtils $authenticationUtils): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        return $this->render('tipo_publicacion/show.html.twig', [
            'tipo_publicacion' => $tipoPublicacion,
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_publicacion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TipoPublicacion $tipoPublicacion, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $lastUsername = $authenticationUtils->getLastUsername();
        $repository=$this->getDoctrine()->getRepository(User::class);
        $min = $repository->findOneBy(['email'=>$lastUsername]);

        $form = $this->createForm(TipoPublicacionType::class, $tipoPublicacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('tipo_publicacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipo_publicacion/edit.html.twig', [
            'tipo_publicacion' => $tipoPublicacion,
            'form' => $form,
            'usuario' =>$min,
            'user' =>$lastUsername,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_publicacion_delete", methods={"POST"})
     */
    public function delete(Request $request, TipoPublicacion $tipoPublicacion, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        if ($this->isCsrfTokenValid('delete'.$tipoPublicacion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tipoPublicacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_publicacion_index', [], Response::HTTP_SEE_OTHER);
    }
}
